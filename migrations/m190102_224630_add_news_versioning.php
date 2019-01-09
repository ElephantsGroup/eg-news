<?php

use yii\db\Migration;
use yii\db\Query;


/**
 * Handles the creation of table `news`.
 */
class m190102_224630_add_news_versioning extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%eg_news_new}}', [
          'id' => $this->integer(11)->notNull(),
		      'version' => $this->integer(11)->notNull(),
          'category_id' => $this->integer(11),
          'update_time' => $this->timestamp(),
          'creation_time' => $this->timestamp(),
          'archive_time' => $this->timestamp(),
          'views' => $this->integer(11)->defaultValue(0),
          'thumb' => $this->string(15)->notNull()->defaultValue('default.png'),
          'author_id' => $this->integer(11),
          'status' => $this->smallInteger(4)->notNull()->defaultValue(0),
      		'PRIMARY KEY (`id`, `version`)'
          ]);
          $this->createTable('{{%eg_news_translation_new}}',[
            'news_id' => $this->integer(11)->notNull(),
			      'version' => $this->integer(11)->notNull(),
            'language' => $this->string(5)->notNull(),
            'title' => $this->string(255),
            'subtitle' => $this->string(255),
            'intro' => $this->text(),
            'description' => $this->text(),
            'PRIMARY KEY (`news_id`, `version`, `language`)'
          ]);

        $db = \Yii::$app->db;
        $query = new Query();
        if ($db->schema->getTableSchema("{{%eg_news}}", true) !== null)
        {
          if ($query->from('{{%eg_news}}')->exists())
            {
              $records = $query->from('{{%eg_news}}')->all();
              foreach ($records as &$item)
              {
                $item['version'] = 1;
              }
              $columns = array_keys($records[0]);
              if ($columns !== null && !empty($columns))
              {
                if ($db->schema->getTableSchema("{{%eg_news_new}}", true) !== null)
                {
                  $this->BatchInsert('{{%eg_news_new}}', $columns, $records);
                }
              }
            }
        }
        if ($db->schema->getTableSchema("{{%eg_news_translation}}", true) !== null)
        {
          if ($query->from('{{%eg_news_translation}}')->exists())
            {
              $records = $query->from('{{%eg_news_translation}}')->all();
              foreach ($records as &$item)
              {
                $item['version'] = 1;
              }
              $columns = array_keys($records[0]);
              if ($columns !== null && !empty($columns))
              {
                if ($db->schema->getTableSchema("{{%eg_news_translation_new}}", true) !== null)
                {
                  $this->BatchInsert('{{%eg_news_translation_new}}', $columns, $records);
                }
              }
            }
        }

        $this->renameTable('{{%eg_news}}', '{{%eg_news_v1}}');
        $this->renameTable('{{%eg_news_new}}', '{{%eg_news}}');
        $this->renameTable('{{%eg_news_translation}}', '{{%eg_news_translation_v1}}');
        $this->renameTable('{{%eg_news_translation_new}}', '{{%eg_news_translation}}');
        $this->addForeignKey('fk_eg_news_category_v1', '{{%eg_news}}', 'category_id', '{{%eg_news_category}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_eg_news_author_v1', '{{%eg_news}}', 'author_id', '{{%user}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_eg_news_translation_v1', '{{%eg_news_translation}}', ['news_id', 'version'], '{{%eg_news}}', ['id', 'version'] , 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropForeignKey('fk_eg_news_translation_v1', '{{%eg_news_translation}}');
      $this->dropTable('{{%eg_news_translation}}');
      $this->dropForeignKey('fk_eg_news_category_v1', '{{%eg_news}}');
      $this->dropForeignKey('fk_eg_news_author_v1', '{{%eg_news}}');
      $this->dropTable('{{%eg_news}}');
      $this->renameTable('{{%eg_news_v1}}', '{{%eg_news}}');
      $this->renameTable('{{%eg_news_translation_v1}}', '{{%eg_news_translation}}');
    }
}
