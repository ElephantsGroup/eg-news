<?php

namespace elephantsGroup\news\models;

use Yii;

/**
 * This is the model class for table "basic3aug_news_translation".
 *
 * @property integer $news_id
 * @property integer $version
 * @property string $language
 * @property string $title
 * @property string $subtitle
 * @property string $intro
 * @property string $description
 *
 * @property News $news
 */
class NewsTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%eg_news_translation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
		$module_base = \Yii::$app->getModule('base');
        return [
            [['news_id', 'version', 'language'], 'required'],
            [['news_id', 'version'], 'integer'],
            [['intro', 'description'], 'string'],
            [['language'], 'string', 'max' => 5],
            [['title', 'subtitle'], 'string', 'max' => 255],
      			[['title'], 'trim'],
      			[['language'], 'default', 'value' => Yii::$app->language],
      			[['language'], 'in', 'range' => array_keys($module_base->languages)],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $module_base = \Yii::$app->getModule('base');
		    $module_news = \Yii::$app->getModule('news');
        return [
            'news_id' => $module_news::t('news', 'News id'),
            'version' => $module_news::t('news', 'Version'),
            'language' => $module_base::t('Language'),
            'title' => $module_base::t('Title'),
            'subtitle' => $module_base::t('Subtitle'),
            'intro' => $module_base::t('Intro'),
            'description' => $module_base::t('Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasOne(News::className(), ['id' => 'news_id', 'version' => 'version']);
    }
}
