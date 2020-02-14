<?php

namespace common\models\news;

/**
 * Статья новости
 *
 * @property int $id
 * @property string $title
 * @property string $body
 * @property string $date_published
 * @property string $original_url
 * @property string $original_html
 * @property int $created_at
 * @property int $updated_at
 *
 * @package common\models\news
 */
class Article extends \common\base\ActiveRecord
{
    /**
     * константы удобно использовать при обращение к полям в ключах массивов и строках
     * field
     */
    public const FIELD_ID = 'id';
    public const FIELD_TITLE = 'title';
    public const FIELD_BODY = 'body';
    public const FIELD_DATE_PUBLISHED = 'date_published';
    public const FIELD_ORIGINAL_URL = 'original_url';
    public const FIELD_ORIGINAL_HTML = 'original_html';
    public const FIELD_CREATED_AT = 'created_at';
    public const FIELD_UPDATED_AT = 'updated_at';

    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::class,
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [[self::FIELD_ORIGINAL_URL], 'required'],
            [[self::FIELD_BODY, self::FIELD_ORIGINAL_HTML], 'string'],
            [[self::FIELD_DATE_PUBLISHED], 'string'],
            [[self::FIELD_CREATED_AT, self::FIELD_UPDATED_AT], 'integer'],
            [[self::FIELD_TITLE, self::FIELD_ORIGINAL_URL], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            self::FIELD_ID => 'ID',
            self::FIELD_TITLE => 'Title',
            self::FIELD_BODY => 'Body',
            self::FIELD_DATE_PUBLISHED => 'Date Published',
            self::FIELD_ORIGINAL_URL => 'Original Url',
            self::FIELD_ORIGINAL_HTML => 'Original Html',
            self::FIELD_CREATED_AT => 'Created At',
            self::FIELD_UPDATED_AT => 'Updated At',
        ];
    }
}