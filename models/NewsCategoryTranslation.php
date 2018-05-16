<?php

namespace elephantsGroup\news\models;

use Yii;

/**
 * This is the model class for table "basic3aug_news_category_translation".
 *
 * @property integer $cat_id
 * @property string $language
 * @property string $title
 *
 * @property NewsCategory $cat
 */
class NewsCategoryTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%eg_news_category_translation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
		$module_base = \Yii::$app->getModule('base');
        return [
            [['cat_id', 'language'], 'required'],
            [['cat_id'], 'integer'],
			[['language'], 'default', 'value' => Yii::$app->language],
			[['language'], 'in', 'range' => array_keys($module_base->languages)],
			[['language'], 'string', 'max' => 5],
            [['title'], 'string', 'max' => 32],
			[['title'], 'trim']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
		$module = \Yii::$app->getModule('base');
        return [
            'cat_id' => $module::t('Category ID'),
            'language' => $module::t('Language'),
            'title' => $module::t('Title'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCat()
    {
        return $this->hasOne(NewsCategory::className(), ['id' => 'cat_id']);
    }
}
