<?php

use yii\db\Schema;
use yii\db\Migration;

class m150803_172218_create_news extends Migration
{

    public function safeUp()
    {
        $this->createTable('{{%eg_news_category}}',[
            'id' => $this->primaryKey(),
            'name' => $this->string(32)->notNull(),
            'logo' => $this->string(32)->notNull()->defaultValue('default.png'),
            'status' => $this->smallInteger(4)->notNull()->defaultValue(0)
        ]);
        $this->createTable('{{%eg_news_category_translation}}',[
            'cat_id' => $this->integer(11),
            'language' => $this->string(5)->notNull(),
            'title' => $this->string(32),
            'PRIMARY KEY (`cat_id`, `language`)'
        ]);
        $this->addForeignKey('fk_eg_news_category_translation', '{{%eg_news_category_translation}}', 'cat_id', '{{%eg_news_category}}', 'id', 'RESTRICT', 'CASCADE');

        $this->createTable('{{%eg_news}}',[
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(11),
            'update_time' => $this->timestamp(),
            'creation_time' => $this->timestamp(),
            'archive_time' => $this->timestamp(),
            'views' => $this->integer(11)->defaultValue(0),
            'thumb' => $this->string(15)->notNull()->defaultValue('default.png'),
            'author_id' => $this->integer(11),
            'status' => $this->smallInteger(4)->notNull()->defaultValue(0)
        ]);
        $this->addForeignKey('fk_eg_news_category', '{{%eg_news}}', 'category_id', '{{%eg_news_category}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_eg_news_author', '{{%eg_news}}', 'author_id', '{{%user}}', 'id', 'SET NULL', 'CASCADE');
        $this->createTable('{{%eg_news_translation}}',[
            'news_id' => $this->integer(11),
            'language' => $this->string(5)->notNull(),
            'title' => $this->string(255),
            'subtitle' => $this->string(255),
            'intro' => $this->text(),
            'description' => $this->text(),
            'PRIMARY KEY (`news_id`, `language`)'
        ]);
        $this->addForeignKey('fk_eg_news_translation', '{{%eg_news_translation}}', 'news_id', '{{%eg_news}}', 'id', 'RESTRICT', 'CASCADE');

        $this->insert('{{%eg_news_category}}', [
            'name' => 'عمومی',
        ]);
        $this->insert('{{%eg_news_category_translation}}', [
            'cat_id' => 1,
            'language' => 'fa-IR',
            'title' => 'عمومی',
        ]);
        $this->insert('{{%eg_news}}', [
            'category_id' => 1,
            'thumb' => 'news-1.png',
            'archive_time' => 1467629406,
            'creation_time' => 1467629406,
            'update_time' => 1467629406,
            'author_id' => 1,
            'status' => 1,
        ]);
        $this->insert('{{%eg_news}}', [
            'category_id' => 1,
            'thumb' => 'news-2.png',
            'archive_time' => 1467629406,
            'creation_time' => 1467629406,
            'update_time' => 1467629406,
            'author_id' => 1,
            'status' => 1,
        ]);
        $this->insert('{{%eg_news}}', [
            'category_id' => 1,
            'thumb' => 'news-3.png',
            'archive_time' => 1467629406,
            'creation_time' => 1467629406,
            'update_time' => 1467629406,
            'author_id' => 1,
            'status' => 1,
        ]);
        $this->insert('{{%eg_news_translation}}', [
            'news_id' => 1,
            'language' => 'fa-IR',
            'title' => 'بروزرسانی فریمورک کلید',
            'subtitle' => 'اعمال آخرین تغییرات بر روی پلت فرم ',
            'intro' => 'آخرین تغییرات بر روی پلت فرم اعمال شد',
            'description' => '<p>فریمورک کلید بروز شد و آخرین تغییرات بر روی پلت فرم اعمال شد .&nbsp;<br />منتظر تغییرات بعدی باشید ...</p>',
        ]);
        $this->insert('{{%eg_news_translation}}', [
            'news_id' => 2,
            'language' => 'fa-IR',
            'title' => 'بروزرسانی فریمورک کلید',
            'subtitle' => 'اعمال آخرین تغییرات بر روی پلت فرم ',
            'intro' => 'آخرین تغییرات بر روی پلت فرم اعمال شد',
            'description' => '<p>فریمورک کلید بروز شد و آخرین تغییرات بر روی پلت فرم اعمال شد .&nbsp;<br />منتظر تغییرات بعدی باشید ...</p>',
        ]);
        $this->insert('{{%eg_news_translation}}', [
            'news_id' => 3,
            'language' => 'fa-IR',
            'title' => 'بروزرسانی فریمورک کلید',
            'subtitle' => 'اعمال آخرین تغییرات بر روی پلت فرم ',
            'intro' => 'آخرین تغییرات بر روی پلت فرم اعمال شد',
            'description' => '<p>فریمورک کلید بروز شد و آخرین تغییرات بر روی پلت فرم اعمال شد .&nbsp;<br />منتظر تغییرات بعدی باشید ...</p>',
        ]);

        $this->insert('{{%auth_item}}', [
            'name' => '/news/admin/*',
            'type' => 2,
            'created_at' => 1467629406,
            'updated_at' => 1467629406
        ]);
        $this->insert('{{%auth_item}}', [
            'name' => '/news/category-admin/*',
            'type' => 2,
            'created_at' => 1467629406,
            'updated_at' => 1467629406
        ]);
        $this->insert('{{%auth_item}}', [
            'name' => '/news/translation/*',
            'type' => 2,
            'created_at' => 1467629406,
            'updated_at' => 1467629406
        ]);
        $this->insert('{{%auth_item}}', [
            'name' => '/news/category-translation/*',
            'type' => 2,
            'created_at' => 1467629406,
            'updated_at' => 1467629406
        ]);
        $this->insert('{{%auth_item}}', [
            'name' => 'news_management',
            'type' => 2,
            'created_at' => 1467629406,
            'updated_at' => 1467629406
        ]);
        $this->insert('{{%auth_item_child}}', [
            'parent' => 'news_management',
            'child' => '/news/admin/*',
        ]);
        $this->insert('{{%auth_item_child}}', [
            'parent' => 'news_management',
            'child' => '/news/category-translation/*',
        ]);
        $this->insert('{{%auth_item_child}}', [
            'parent' => 'news_management',
            'child' => '/news/translation/*',
        ]);
        $this->insert('{{%auth_item_child}}', [
            'parent' => 'news_management',
            'child' => '/news/category-admin/*',
        ]);
        $this->insert('{{%auth_item}}', [
            'name' => 'news_manager',
            'type' => 1,
            'created_at' => 1467629406,
            'updated_at' => 1467629406
        ]);
        $this->insert('{{%auth_item_child}}', [
            'parent' => 'news_manager',
            'child' => 'news_management',
        ]);
        $this->insert('{{%auth_item_child}}', [
            'parent' => 'super_admin',
            'child' => 'news_manager',
        ]);
    }

    public function safeDown()
    {
        $this->delete('{{%auth_item_child}}', [
            'parent' => 'super_admin',
            'child' => 'news_manager',
        ]);
        $this->delete('{{%auth_item_child}}', [
            'parent' => 'news_manager',
            'child' => 'news_management',
        ]);
        $this->delete('{{%auth_item}}', [
            'name' => 'news_manager',
            'type' => 1,
        ]);
        $this->delete('{{%auth_item_child}}', [
            'parent' => 'news_management',
            'child' => '/news/category-translation/*',
        ]);
        $this->delete('{{%auth_item_child}}', [
            'parent' => 'news_management',
            'child' => '/news/translation/*',
        ]);
        $this->delete('{{%auth_item_child}}', [
            'parent' => 'news_management',
            'child' => '/news/category-admin/*',
        ]);
        $this->delete('{{%auth_item_child}}', [
            'parent' => 'news_management',
            'child' => '/news/admin/*',
        ]);
        $this->delete('{{%auth_item}}', [
            'name' => 'news_management',
            'type' => 2,
        ]);
        $this->delete('{{%auth_item}}', [
            'name' => '/news/category-admin/*',
            'type' => 2,
        ]);
        $this->delete('{{%auth_item}}', [
            'name' => '/news/admin/*',
            'type' => 2,
        ]);
        $this->delete('{{%auth_item}}', [
            'name' => '/news/category-translation/*',
            'type' => 2,
        ]);
        $this->delete('{{%auth_item}}', [
            'name' => '/news/translation/*',
            'type' => 2,
        ]);

        $this->dropForeignKey('fk_eg_news_translation', '{{%eg_news_translation}}');
        $this->dropTable('{{%eg_news_translation}}');
        $this->dropForeignKey('fk_eg_news_category', '{{%eg_news}}');
        $this->dropForeignKey('fk_eg_news_author', '{{%eg_news}}');
        $this->dropTable('{{%eg_news}}');
        $this->dropForeignKey('fk_eg_news_category_translation', '{{%eg_news_category_translation}}');
        $this->dropTable('{{%eg_news_category_translation}}');
        $this->dropTable('{{%eg_news_category}}');
    }
}
