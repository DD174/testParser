<?php

use console\models\news\Article;

class m200214_100020_news_article extends \console\base\Migration
{
    /**
     * @var string
     */
    private string $tableArticle;

    /**
     * @inheritDoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->tableArticle = Article::tableName();
    }

    /**
     * @inheritDoc
     * @throws \yii\base\NotSupportedException
     */
    public function up()
    {
        $this->createTable(
            $this->tableArticle,
            [
                Article::FIELD_ID => $this->primaryKey(),
                Article::FIELD_TITLE => $this->string(),
                Article::FIELD_BODY => $this->text(),
                Article::FIELD_DATE_PUBLISHED => $this->dateTime(),
                Article::FIELD_ORIGINAL_URL => $this->string()->notNull(),
                Article::FIELD_ORIGINAL_HTML => $this->longBlob(),
                Article::FIELD_CREATED_AT => $this->integer()->notNull(),
                Article::FIELD_UPDATED_AT => $this->integer()->notNull(),
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function down()
    {
        $this->dropTable($this->tableArticle);
    }
}
