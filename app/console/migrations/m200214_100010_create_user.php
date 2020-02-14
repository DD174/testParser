<?php

use console\models\User;

class m200214_100010_create_user extends \console\base\Migration
{
    /**
     * @var string
     */
    private string $tableUser;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->tableUser = User::tableName();
    }

    /**
     * @inheritDoc
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $this->insert($this->tableUser, [
            'id' => 1,
            'username' => 'webmaster',
            'email' => 'webmaster@example.com',
            'password_hash' => Yii::$app->getSecurity()->generatePasswordHash('webmaster'),
            'auth_key' => Yii::$app->getSecurity()->generateRandomString(),
            'status' => User::STATUS_ACTIVE,
            'created_at' => time(),
            'updated_at' => time()
        ]);
        $this->insert('{{%user}}', [
            'id' => 2,
            'username' => 'manager',
            'email' => 'manager@example.com',
            'password_hash' => Yii::$app->getSecurity()->generatePasswordHash('manager'),
            'auth_key' => Yii::$app->getSecurity()->generateRandomString(),
            'status' => User::STATUS_ACTIVE,
            'created_at' => time(),
            'updated_at' => time()
        ]);
        $this->insert('{{%user}}', [
            'id' => 3,
            'username' => 'user',
            'email' => 'user@example.com',
            'password_hash' => Yii::$app->getSecurity()->generatePasswordHash('user'),
            'auth_key' => Yii::$app->getSecurity()->generateRandomString(),
            'status' => User::STATUS_ACTIVE,
            'created_at' => time(),
            'updated_at' => time()
        ]);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->delete('{{%user}}', [
            'id' => [1, 2, 3]
        ]);
    }
}
