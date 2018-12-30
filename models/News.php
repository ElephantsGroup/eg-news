<?php

namespace elephantsGroup\news\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "basic3aug_news".
 *
 * @property integer $id
 * @property integer $version
 * @property integer $category_id
 * @property string $creation_time
 * @property string $update_time
 * @property string $archive_time
 * @property integer $views
 * @property string $thumb
 * @property integer $author_id
 * @property integer $status
 *
 * @property NewsCategory $category
 * @property NewsTranslation[] $newsTranslations
 */
class News extends \yii\db\ActiveRecord
{
	public $image_file;
	public $archive_time_time;

	public static $upload_url;
  public static $upload_path;

	public static $_STATUS_SUBMITTED = 0;
	public static $_STATUS_CONFIRMED = 1;
	public static $_STATUS_REJECTED = 2;
  public static $_STATUS_ARCHIVED = 3;
  public static $_STATUS_EDITED = 4;

	public function init()
    {
        self::$upload_path = str_replace('/backend', '', Yii::getAlias('@webroot')) . '/uploads/eg-news/news/';
        self::$upload_url = str_replace('/backend', '', Yii::getAlias('@web')) . '/uploads/eg-news/news/';
        parent::init();
    }

	public static function getStatus()
	{
		$module = \Yii::$app->getModule('base');
		return array(
			self::$_STATUS_SUBMITTED => $module::t('Submitted'),
			self::$_STATUS_CONFIRMED => $module::t('Confirmed'),
			self::$_STATUS_REJECTED => $module::t('Rejected'),
			self::$_STATUS_ARCHIVED => $module::t('Archived'),
			self::$_STATUS_EDITED => $module::t('Edited'),
		);
		//return [$_SUBMITTED,$_CONFIRMED,$_REJECTED,$_ARCHIVED];
	}

    /**
     * @inheritdoc
     */

	public static function tableName()
    {
        return '{{%eg_news}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'version', 'category_id', 'views', 'author_id', 'status'], 'integer'],
            [['creation_time', 'update_time', 'archive_time'], 'date', 'format'=>'php:Y-m-d H:i:s'],
            [['image_file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'checkExtensionByMimeType'=>false],
            [['id', 'version', 'category_id'], 'required'],
            [['archive_time_time'], 'string', 'max' => 11],
            [['thumb'], 'default', 'value'=>'default.png'],
			[['status'], 'default', 'value' => self::$_STATUS_SUBMITTED],
			[['status'], 'in', 'range' => array_keys(self::getStatus())],
			[['update_time'], 'default', 'value' => (new \DateTime)->setTimestamp(time())->setTimezone(new \DateTimeZone('Iran'))->format('Y-m-d H:i:s')],
            [['creation_time'], 'default', 'value' => (new \DateTime)->setTimestamp(time())->setTimezone(new \DateTimeZone('Iran'))->format('Y-m-d H:i:s')]
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
            'version' => $module::t('Version'),
            'category_id' => $module::t('Category ID'),
            'creation_time' => $module::t('Creation Time'),
            'update_time' => $module::t('Update Time'),
            'archive_time' => $module::t('Archive Time'),
            'views' => $module::t('Views'),
            'thumb' => $module::t('Thumbnail'),
            'author_id' => $module::t('Author ID'),
            'status' => $module::t('Status'),
			'title' => $module::t('Title'),
        ];
    }

	public function getTitle()
	{
		$module = \Yii::$app->getModule('news');
		$value = $module::t('news', 'Undefined');
		$translate = NewsTranslation::findOne(['news_id'=>$this->id, 'language'=>Yii::$app->language]);
		if($translate)
			$value = $translate->title;
		return $value;
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(NewsCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
	public function getVersions()
    {
        return NewsTranslation::find()->select('version')->where(['news_id'=>$this->id])->all();
    }

    public function getTranslations()
    {
        return $this->hasMany(NewsTranslation::className(), ['news_id' => 'id', 'version' => 'version']);
    }

    public function getTranslationByLang()
    {
        return $this->hasOne(NewsTranslation::className(), ['news_id' => 'id', 'version' => 'version'])->where('language = :language', [':language' => Yii::$app->controller->language]);
    }

	public static function find()
	{
		return new NewsQuery(get_called_class());
	}

	public function beforeSave($insert)
	{
		$date = new \DateTime();
		$date->setTimestamp(time());
		$date->setTimezone(new \DateTimezone('Iran'));
		$this->update_time = $date->format('Y-m-d H:i:s');
		if($this->isNewRecord)
			$this->creation_time = $date->format('Y-m-d H:i:s');
		return parent::beforeSave($insert);
	}

	public function afterSave($insert, $changedAttributes)
    {
//			var_dump( $this->version);die;
        if($this->image_file)
        {
					$dir = self::$upload_path . $this->id . '/';
					if(!file_exists($dir))
						mkdir($dir, 0777, true);
		            $file_name = 'news-' . $this->id . '-' . $this->version . '.' . $this->image_file->extension;
		            $this->image_file->saveAs($dir . $file_name);
		            $this->updateAttributes(['thumb' => $file_name]);
		     }
        return parent::afterSave($insert, $changedAttributes);
    }

	public function beforeDelete()
	{
		foreach($this->translations as $newsTranslations)
			$newsTranslations->delete();

		if($this->thumb != 'default.png')
		{
			$file_path = self::$upload_path . $this->id . '/' . $this->thumb;
			if(file_exists($file_path))
				unlink($file_path);
		}
		return parent::beforeDelete();
	}

	public function getCanBeConfirmed()
	{
		return (($this->status == self::$_STATUS_SUBMITTED || $this->status == self::$_STATUS_ARCHIVED || $this->status == self::$_STATUS_REJECTED)
			&& Yii::$app->user &&(Yii::$app->user->identity->isAdmin || Yii::$app->user->id == $this->author_id)
		);
	}

	public function Confirm()
	{
		if($this->getCanBeConfirmed())
		{
			$this->updateAttributes(['status' => self::$_STATUS_CONFIRMED]);
			return true;
		}
		return false;
	}

	public function getCanBeRejected()
	{
		return (($this->status == self::$_STATUS_SUBMITTED || $this->status == self::$_STATUS_CONFIRMED || $this->status == self::$_STATUS_ARCHIVED)
			&& Yii::$app->user &&(Yii::$app->user->identity->isAdmin || Yii::$app->user->id == $this->author_id)
		);
	}

	public function Reject()
	{
		if($this->getCanBeRejected())
		{
			$this->updateAttributes(['status' => self::$_STATUS_REJECTED]);
			return true;
		}
		return false;
	}

	public function getCanBeArchived()
	{
		return (($this->status == self::$_STATUS_SUBMITTED || $this->status == self::$_STATUS_CONFIRMED || $this->status == self::$_STATUS_REJECTED)
			&& Yii::$app->user &&(Yii::$app->user->identity->isAdmin || Yii::$app->user->id == $this->author_id)
		);
	}

	public function Archive()
	{
		if($this->getCanBeArchived())
		{
			$this->updateAttributes(['status' => self::$_STATUS_ARCHIVED]);
			return true;
		}
		return false;
	}
}

class NewsQuery extends ActiveQuery
{
    public function archived()
    {
        return $this->andWhere(['status' => News::$_STATUS_ARCHIVED]);
    }

    public function confirmed()
    {
        return $this->andWhere(['status' => News::$_STATUS_CONFIRMED]);
    }

	public function notEdited()
    {
        return $this->andWhere(['!=', 'status', News::$_STATUS_EDITED]);
    }
}
