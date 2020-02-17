<?php

namespace backend\modules\news\models;

use simplehtmldom\HtmlDocument;
use simplehtmldom\HtmlNode;

/**
 *
 */
class Article extends \common\models\news\Article
{
    public function beforeSave($insert)
    {
        if (true || $insert) {
//            /** @var \simplehtmldom\HtmlDocument $sHtml */
            if (!$str = $this->original_html) {
                $str = file_get_contents($this->original_url);
            }
            if ($str !== false) {
                $this->original_html = $str;//iconv('windows-1251', 'utf-8//IGNORE', $str);
                $html = new HtmlDocument(
                    $str
                );

                /** @var HtmlDocument $article */
                if (!$article = $html->find('article[itemprop=articleBody]', 0)) {
                    throw new \DomainException('На странице новости не найдена статья. Новость # . ' . $this->id);
                }
                /** @var HtmlNode $el */
                $el = $article->find('h1', 0);
                if (!$this->title = ($el->innertext() ?? null)) {
                    throw new \DomainException('На странице новости не найден заголовок. Новость # . ' . $this->id);
                }
                /** @var HtmlNode[] $es */
                $es = $article->find('p, h2');
                if (!is_array($es)) {
                    throw new \DomainException('На странице новости не найден контент новости. Новость # . ' . $this->id);
                }
                $body = [];
                foreach ($es as $el) {
                    $body[] = $el->outertext();
                }
                $this->body = implode("\n", $body);
            } else {
                $this->original_html = null;
            }
        }

        return parent::beforeSave($insert);
    }
}