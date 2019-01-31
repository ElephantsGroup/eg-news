<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Handles adding publish_time to table `{{%news}}`.
 */
class m190131_171533_add_publish_time_column_to_news_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $db = \Yii::$app->db;
      $query = new Query();
      if ($db->schema->getTableSchema("{{%eg_news}}", true) !== null)
      {
        if ($query->from('{{%eg_news}}')->exists())
        {
          $this->addColumn('{{%eg_news}}', 'publish_time', $this->timestamp()->after('archive_time'));
          $this->update("{{%eg_news}}", ['publish_time' => new \yii\db\Expression('creation_time')]);
        }
      }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('{{%eg_news}}', 'publish_time');
    }
}
