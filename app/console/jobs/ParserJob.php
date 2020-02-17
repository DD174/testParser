<?php


namespace console\jobs;


use backend\modules\news\models\Article;
use simplehtmldom\HtmlDocument;
use simplehtmldom\HtmlNode;

/**
 * Job для парсинга контента новостей
 * - если в исходной страницы нет оригинала html, то скачает страницу с адреса
 * - применяет шаблон парсера к сохраненной статье
 *
 * TODO: починить кодировку ссылок внутри новостиs
 * TODO: simplehtmldom падает, если в тексте новости встречается что-то "плохое", возможно получится решить изменение кодировки источника
 * TODO: сохранить дату публикации
 * TODO: нужно релизовать механизм обновления скаченной страницы
 *
 * @package console\jobs
 */
class ParserJob implements \yii\queue\JobInterface
{
    private const INTERFAX = 'https://www.interfax.ru';

    /**
     * @var Article
     */
    private Article $article;

    /**
     * @var string
     */
    private string $baseUrl;

    /**
     * ParserJob constructor.
     * @param Article $article
     */
    public function __construct(Article $article)
    {
        $this->article = $article;

        if (!preg_match("#^(http(s)?://[^/]+)/.+$#", $this->article->original_url, $matches)) {
            throw new \DomainException('В новости указан некорректный адрес страницы. Статья #' . $this->article->id);
        }
        $this->baseUrl = str_replace('http:', 'https:', $matches[1]);
    }

    /**
     * непосредственно загружаем исходную страницу и парсим ее
     */
    public function run()
    {
        if (!$this->isCorrectSource()) {
            throw new \DomainException('В новости указан не верный источник. Статья #' . $this->article->id);
        }

        if ($this->isInterfax()) {
//            $this->article->refresh();
            if (!$str = $this->article->original_html) {
                $str = file_get_contents($this->article->original_url);
            }
            if ($str === false) {
                throw new \DomainException('Не удалось загрузить страницу новости. Статья #' . $this->article->id);
            }

            $this->article->original_html = $str;
            $html = new HtmlDocument(
                $str
            );

            /** @var HtmlDocument $article */
            if (!$article = $html->find('article[itemprop=articleBody]', 0)) {
                throw new \DomainException('На странице новости не найдена новость. Статья # . ' . $this->article->id);
            }

            /** @var HtmlNode $el */
            $el = $article->find('h1', 0);
            if (!$this->article->title = ($el->innertext() ?? null)) {
                throw new \DomainException(
                    'На странице новости не найден заголовок. Статья # . ' . $this->article->id
                );
            }
            /** @var HtmlNode[] $es */
            $es = $article->find('p, h2');
            if (!is_array($es)) {
                throw new \DomainException(
                    'На странице новости не найден контент новости. Статья # . ' . $this->article->id
                );
            }
            $body = [];
            foreach ($es as $el) {
                $body[] = $el->outertext();
            }

            if (!$body) {
                throw new \DomainException(
                    'В найденном контенте новости нет текста. Статья # . ' . $this->article->id
                );
            }

            $this->article->body = implode("\n", $body);
            $this->article->createJob = false;
            $this->article->save();

            $images = [];
            /** @var HtmlNode[] $es */
            $es = $article->find('figure > img');
            foreach ($es as $img) {
                if ($img->tag !== 'img') {
                    continue;
                }
                $file = tmpfile();
                fwrite($file, file_get_contents($this->baseUrl . $img->getAttribute('src')));
                if ($path = stream_get_meta_data($file)['uri'] ?? null) {
                    $images[] = $path;
                }
            }

            $this->article->replaceImages($images);
        }
    }

    /**
     * Проверка на корректный адрес страницы новости
     * @return bool
     */
    private function isCorrectSource()
    {
        return $this->baseUrl === self::INTERFAX;
    }

    /**
     * Для того, чтобы точно знать, что можно применить шаблон для интерфакса
     * @return bool
     */
    private function isInterfax()
    {
        return $this->baseUrl === self::INTERFAX;
    }

    /**
     * @inheritDoc
     */
    public function execute($queue)
    {
        $this->run();
    }
}