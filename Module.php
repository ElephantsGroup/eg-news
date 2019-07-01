<?php

namespace elephantsGroup\news;

/*
	Module news for Yii 2
	Authors : Jalal Jaberi, Arezou Zahedi Majd, Arvin Firouzi
	Website : http://elephantsgroup.com
*/

use Yii;

class Module extends \yii\base\Module
{
    public $enabled_like;
    public $enabled_follow;
    public $enabled_comment;
    public $enabled_rating;
    public $page_size = 10;

    public $thumbIconName = 'icon.jpg';
    public $thumbIconWidth = 80;
    public $thumbIconHeight = 80;
    public $thumbLargName = 'news-l.jpg';
    public $thumbLargWidth = 540;
    public $thumbLargHeight = 540;
    public $thumbMediumName = 'news-m.jpg';
    public $thumbMediumWidth = 220;
    public $thumbMediumHeight = 220;

    public $thumbSize = [];

    public function init()
    {
        parent::init();

        if (empty(Yii::$app->i18n->translations['news']))
		{
            Yii::$app->i18n->translations['news'] =
			[
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => __DIR__ . '/messages',
                //'forceTranslation' => true,
            ];
        }
        if (empty(Yii::$app->i18n->translations['news_cat']))
		{
            Yii::$app->i18n->translations['news_cat'] =
			[
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => __DIR__ . '/messages',
                //'forceTranslation' => true,
            ];
        }
        if (empty(Yii::$app->i18n->translations['news_params']))
		{
            Yii::$app->i18n->translations['news_params'] =
			[
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => __DIR__ . '/messages',
                //'forceTranslation' => true,
            ];
        }
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return \Yii::t($category, $message, $params, $language);
    }
}
