<?php

namespace elephantsGroup\news\components;

use Yii;
use elephantsGroup\news\models\News;
use elephantsGroup\news\models\NewsTranslation;
use yii\base\Widget;
use yii\helpers\Html;

class LastNews extends Widget
{
	public $category_id = 0;
	public $number = 3;
	public $language;
	public $title;
	public $subtitle;
	public $title_is_link = true;
	public $news_title_is_link = true;
	public $show_global_more = false;
	public $global_more_text = '';
	public $show_archive_button = false;
	public $archive_button_text = '';
	public $view_file = 'last_news';

	protected $_news = [];

	public function init()
	{
		$module = Yii::$app->getModule('news');
		if(!isset($this->language) || !$this->language)
			$this->language = Yii::$app->language;
		if(!isset($this->title) || !$this->title)
			$this->title = Yii::t('news_params', 'Last News Title');
		if(!isset($this->subtitle))
			$this->subtitle = Yii::t('news_params', 'Last News Subtitle');
		if(!isset($this->global_more_text))
			$this->global_more_text = Yii::t('news_params', 'Global More Text');
		if(!isset($this->archive_button_text))
			$this->archive_button_text = Yii::t('news_params', 'Archive Button Text');
        if(!isset($this->view_file) || !$this->view_file)
			$this->view_file = Yii::t('news_params', 'View File');
	}

    public function run()
	{
		$date = new \DateTime('now');
		$date->setTimezone(new \DateTimezone('Iran'));
		$now = $date->format('Y-m-d H:m:s');

		if($this->category_id != 0)
			$news = News::find()->where(['<=', 'publish_time' , $now ])->confirmed()->where(['category_id' => $this->category_id])->orderBy(['creation_time'=>SORT_DESC])->all();
		else
			$news = News::find()->where(['<=', 'publish_time' , $now ])->confirmed()->orderBy(['creation_time'=>SORT_DESC])->all();
		$i = 0;
		foreach($news as $news_item)
		{
			if($i == $this->number) break;
			$max_version_translation = NewsTranslation::find()->where(['news_id' => $news_item->id, 'language' => $this->language])->max('version');
			$translation = NewsTranslation::findOne(array('news_id'=>$news_item->id, 'language'=>$this->language, 'version' => $max_version_translation));
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

		return $this->render( $this->view_file, [
			'news' => $this->_news,
			'last_news_title' => $this->title,
			'last_news_subtitle' => $this->subtitle,
			'title_is_link' => $this->title_is_link,
			'news_title_is_link' => $this->news_title_is_link,
			'language' => $this->language,
			'show_global_more' => $this->show_global_more,
			'global_more_text' => $this->global_more_text,
			'show_archive_button' => $this->show_archive_button,
			'archive_button_text' => $this->archive_button_text,
		]);
	}
}
