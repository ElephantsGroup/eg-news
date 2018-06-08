<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m180608_142642_add_news_management
 */
class m180608_142642_add_news_management extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$db = \Yii::$app->db;
		$query = new Query();
        if ($db->schema->getTableSchema("{{%auth_item}}", true) !== null)
		{
			if (!$query->from('{{%auth_item}}')->where(['name' => '/news/admin/*'])->exists())
				$this->insert('{{%auth_item}}', [
					'name'			=> '/news/admin/*',
					'type'			=> 2,
					'created_at'	=> time(),
					'updated_at'	=> time()
				]);
			if (!$query->from('{{%auth_item}}')->where(['name' => '/news/category-admin/*'])->exists())
				$this->insert('{{%auth_item}}', [
					'name'			=> '/news/category-admin/*',
					'type'			=> 2,
					'created_at'	=> time(),
					'updated_at'	=> time()
				]);
			if (!$query->from('{{%auth_item}}')->where(['name' => '/news/translation/*'])->exists())
				$this->insert('{{%auth_item}}', [
					'name'			=> '/news/translation/*',
					'type'			=> 2,
					'created_at'	=> time(),
					'updated_at'	=> time()
				]);
			if (!$query->from('{{%auth_item}}')->where(['name' => '/news/category-translation/*'])->exists())
				$this->insert('{{%auth_item}}', [
					'name'			=> '/news/category-translation/*',
					'type'			=> 2,
					'created_at'	=> time(),
					'updated_at'	=> time()
				]);
			if (!$query->from('{{%auth_item}}')->where(['name' => 'news_management'])->exists())
				$this->insert('{{%auth_item}}', [
					'name'			=> 'news_management',
					'type'			=> 2,
					'created_at'	=> time(),
					'updated_at'	=> time()
				]);
			if (!$query->from('{{%auth_item}}')->where(['name' => 'news_manager'])->exists())
				$this->insert('{{%auth_item}}', [
					'name'			=> 'news_manager',
					'type'			=> 1,
					'created_at'	=> time(),
					'updated_at'	=> time()
				]);
			if (!$query->from('{{%auth_item}}')->where(['name' => 'administrator'])->exists())
				$this->insert('{{%auth_item}}', [
					'name'			=> 'administrator',
					'type'			=> 1,
					'created_at'	=> time(),
					'updated_at'	=> time()
				]);
		}
        if ($db->schema->getTableSchema("{{%auth_item_child}}", true) !== null)
		{
			if (!$query->from('{{%auth_item_child}}')->where(['parent' => 'news_management', 'child' => '/news/admin/*'])->exists())
				$this->insert('{{%auth_item_child}}', [
					'parent'	=> 'news_management',
					'child'		=> '/news/admin/*'
				]);
			if (!$query->from('{{%auth_item_child}}')->where(['parent' => 'news_management', 'child' => '/news/category-admin/*'])->exists())
				$this->insert('{{%auth_item_child}}', [
					'parent'	=> 'news_management',
					'child'		=> '/news/category-admin/*'
				]);
			if (!$query->from('{{%auth_item_child}}')->where(['parent' => 'news_management', 'child' => '/news/translation/*'])->exists())
				$this->insert('{{%auth_item_child}}', [
					'parent'	=> 'news_management',
					'child'		=> '/news/translation/*'
				]);
			if (!$query->from('{{%auth_item_child}}')->where(['parent' => 'news_management', 'child' => '/news/category-translation/*'])->exists())
				$this->insert('{{%auth_item_child}}', [
					'parent'	=> 'news_management',
					'child'		=> '/news/category-translation/*'
				]);
			if (!$query->from('{{%auth_item_child}}')->where(['parent' => 'news_manager', 'child' => 'news_management'])->exists())
				$this->insert('{{%auth_item_child}}', [
					'parent'	=> 'news_manager',
					'child'		=> 'news_management'
				]);
			if (!$query->from('{{%auth_item_child}}')->where(['parent' => 'administrator', 'child' => 'news_manager'])->exists())
				$this->insert('{{%auth_item_child}}', [
					'parent'	=> 'administrator',
					'child'		=> 'news_manager'
				]);
		}
        if ($db->schema->getTableSchema("{{%auth_assignment}}", true) !== null)
		{
			if (!$query->from('{{%auth_assignment}}')->where(['item_name' => 'administrator', 'user_id' => 1])->exists())
				$this->insert('{{%auth_assignment}}', [
					'item_name'	=> 'administrator',
					'user_id'	=> 1,
					'created_at' => time()
				]);
		}
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		// it's not safe to remove auth data in migration down
    }
}
