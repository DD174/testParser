<?php

use backend\modules\news\models\Article;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model Article */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="article-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            $model::FIELD_ID,
            $model::FIELD_TITLE,
            $model::FIELD_BODY . ':ntext',
            $model::FIELD_DATE_PUBLISHED . ':datetime',
            $model::FIELD_ORIGINAL_URL . ':url',
            [
                'attribute' => $model::FIELD_ORIGINAL_HTML,
                'value' => \yii\helpers\StringHelper::truncate($model->original_html, 100),
            ],
            $model::FIELD_CREATED_AT . ':datetime',
            $model::FIELD_UPDATED_AT . ':datetime',
            [
                'label' => 'Images',
                'format' => 'html',
                'value' => function (Article $model) {
                    $str = '';
                    foreach ($model->getBehaviorGallery()->getImages() as $image) {
                        $str .= '<img src="' . $image->getUrl('preview') . ' " />';
                    }
                    return $str;
                }
            ]
        ],
    ]) ?>

</div>
