<?php

namespace elephantsGroup\news\models;

use Yii;

/**
 * This is the model class for table "basic3aug_news_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $logo
 * @property integer $status
 *
 * @property News[] $news
 * @property NewsCategoryTranslation[] $newsCategoryTranslations
 */
class NewsCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $image_file;
	
	public static $upload_url;
    public static $upload_path;
    
	public static $_STATUS_INACTIVE = 0;
	public static $_STATUS_ACTIVE = 1;
	
	public function init()
    {
        self::$upload_path = str_replace('/backend', '', Yii::getAlias('@webroot')) . '/uploads/eg-news/news-category/';
        self::$upload_url = str_replace('/backend', '', Yii::getAlias('@web')) . '/uploads/eg-news/news-category/';
        parent::init();
    }

	public static function getStatus()
	{
		$module = \Yii::$app->getModule('base');
		return array(
			self::$_STATUS_INACTIVE => $module::t('Inactive'),
			self::$_STATUS_ACTIVE => $module::t('Active'),
		);
	}
	
    public static function tableName()
    {
        return '{{%eg_news_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status'], 'integer'],
			[['status'], 'default', 'value' => self::$_STATUS_INACTIVE],
			[['status'], 'in', 'range' => array_keys(self::getStatus())],
            [['image_file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'checkExtensionByMimeType'=>false],
            [['name', 'logo'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
		$module = \Yii::$app->getModule('base');
        return [
            'id' => $module::t('ID'),
            'name' => $module::t('Name'),
            'logo' => $module::t('Logo'),
            'status' => $module::t('Status'),
			'title' => $module::t('Title'),
        ];
    }

	public function getTitle()
	{
		$module = \Yii::$app->getModule('base');
		$value = $module::t('Undefined');
		$translate = NewsCategoryTranslation::findOne(['cat_id'=>$this->id, 'language'=>Yii::$app->language]);
		if($translate)
			$value = $translate->title;
		return $value;
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(News::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(NewsCategoryTranslation::className(), ['cat_id' => 'id']);
    }

    public function getTranslationByLang()
    {
        return $this->hasOne(NewsCategoryTranslation::className(), ['cat_id' => 'id'])->where('language = :language', [':language' => Yii::$app->controller->language]);
    }
	
	public function afterSave($insert, $changedAttributes)
    {
        if($this->image_file)
        {
			$dir = self::$upload_path . $this->id . '/';
			if(!file_exists($dir))
				mkdir($dir, 0777, true);
            $file_name = 'news-cat-' . $this->id . '.' . $this->image_file->extension;
            $this->image_file->saveAs($dir . $file_name);
            $this->updateAttributes(['logo' => $file_name]);
        }
        return parent::afterSave($insert, $changedAttributes);
    }

	public function beforeDelete()
	{
		foreach($this->translations as $translation)
			$translation->delete();

		if($this->logo != 'default.png')
		{
			$file_path = self::$upload_path . $this->id . '/' . $this->logo;	
			if(file_exists($file_path))
				unlink($file_path);
		}
		return parent::beforeDelete();
	}
}
