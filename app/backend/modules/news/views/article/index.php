<?php

use backend\modules\news\models\Article;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Articles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Article', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= /** @noinspection PhpUnhandledExceptionInspection */
    GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\ActionColumn'],
                Article::FIELD_ID,
                Article::FIELD_TITLE,
                [
                    'attribute' => Article::FIELD_BODY,
                    'value' => function (Article $article) {
                        return \yii\helpers\StringHelper::truncate($article->body, 100);
                    }
                ],
                Article::FIELD_DATE_PUBLISHED,
                Article::FIELD_ORIGINAL_URL . ':url',
            ],
        ]
    ); ?>


</div>
