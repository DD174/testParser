<?php

namespace backend\modules\news\models;

/**
 *
 */
class Article extends \common\models\news\Article
{
    /**
     * Признак того, что после сохранения нужно создать задание на парсинг
     * @var bool
     */
    public bool $createJob = false;

    /**
     * @inheritDoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->createJob) {
            $job = new \console\jobs\ParserJob($this);

            // TODO: добавлять в очередь, а не запускать :)
            $job->run();
//            \Yii::$app->queue->push($job);
        }
    }

    /**
     * TODO по хорошему нужно "обновлять" только изменившиеся картинки, а не удалять все, а после добавлять
     * @param string[] $images - в строке путь до файла на диске
     */
    public function replaceImages(array $images)
    {
        if ($oldImages = $this->getBehaviorGallery()->getImages()) {
            $this->getBehaviorGallery()->deleteImages(\yii\helpers\ArrayHelper::map($oldImages, 'id', 'id'));
        }
        foreach ($images as $image) {
            $this->getBehaviorGallery()->addImage($image);
        }
    }
}