<?php


namespace console\base;


class Migration extends \yii\db\Migration
{
    /**
     * @inheritDoc
     */
    public function createTable($table, $columns, $options = null)
    {
        if ($options === null && $this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        parent::createTable($table, $columns, $options);
    }

    /**
     * Creates a long blob column.
     * @return \yii\db\ColumnSchemaBuilder the column instance which can be further customized.
     * @throws \yii\base\NotSupportedException
     */
    public function longBlob()
    {
        if ($this->db->driverName === 'mysql') {
            return $this->getDb()->getSchema()->createColumnSchemaBuilder('longblob');
        }

        return $this->text();
    }
}