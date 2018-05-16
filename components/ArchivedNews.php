<?php

namespace elephantsGroup\news\components;

use Yii;
use elephantsGroup\news\models\News;
use elephantsGroup\news\models\NewsTranslation;
use yii\base\Widget;
use yii\helpers\Html;

class ArchivedNews extends Widget
{
	public $number = 100;
	public $language;
	public $title;
	public $subtitle;
    public $view_file = 'archived_news';

	protected $_news = [];

	public function init()
	{
		if(!isset($this->language) || !$this->language)
			$this->language = Yii::$app->language;
		if(!isset($this->title) || !$this->title)
			$this->title = Yii::t('news_params', 'Archived News Title');
		if(!isset($this->subtitle) || !$this->subtitle)
			$this->subtitle = Yii::t('news_params', 'Archived News Subtitle');
        if(!isset($this->view_file) || !$this->view_file)
            $this->view_file = Yii::t('news_params', 'View File');
	}

    public function run()
	{
		$news = News::find()->archived()->orderBy(['creation_time'=>SORT_DESC])->all();
		$i = 0;
		foreach($news as $news_item)
		{
			if($i == $this->number) break;
			$translation = NewsTranslation::findOne(array('news_id'=>$news_item->id, 'language'=>$this->language));
			if($translation)
			{
				$this->_news[] = [
				    'id' => $news_item['id'],
                    'thumb' => News::$upload_url . $news_item['id'] . '/' . $news_item['thumb'],
                    'title' => $translation->title,
                    'subtitle' => $translation->subtitle,
                    'intro' => $translation->intro
                ];
				$i++;
			}
		}
		return $this->render($this->view_file, [
		    'news'=>$this->_news,
            'last_news_title'=>$this->title,
            'last_news_subtitle'=>$this->subtitle
        ]);
	}
}