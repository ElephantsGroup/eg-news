<?php

namespace elephantsGroup\news\controllers;

use elephantsGroup\news\models\NewsCategory;
use elephantsGroup\news\models\NewsCategoryTranslation;
use elephantsGroup\gallery\models\Category;
use elephantsGroup\gallery\models\CategoryTranslation;
use Yii;
//use yii\web\Controller;
use elephantsGroup\news\models\News;
use elephantsGroup\news\models\NewsTranslation;
use elephantsGroup\stat\models\Stat;
use elephantsGroup\base\EGController;
use elephantsGroup\jdf\Jdf;

class CatController extends EGController
{
	private function getBeginDate($lang, $begin_time = null)
	{
		if( $begin_time == null)
		{
			if($lang == 'fa-IR')
			{
				$date = new \DateTime();
				$now_date = Jdf::jdate('Y/m/d', time(), '', 'Iran', 'en');
				$year = (int)(substr($now_date, 0, 4));
				$month = (int)(substr($now_date, 5, 2));
				$date->setTimestamp(Jdf::jmktime(0, 0, 0, $month, 1, $year));
				$from = $date->format('Y-m-d');
			}else
			{
				$date = new \DateTime('first day of this month');
				//$date->setTimestamp();
				$date->setTimezone(new \DateTimezone('Iran'));
				$from = $date->format('Y-m-d');
			}	
			
		}else
		{
			if($lang == 'fa-IR')
			{
				$date = new \DateTime();
				$year = (int)(substr($begin_time, 0, 4));
				$month = (int)(substr($begin_time, 5, 2));
				$day = (int)(substr($begin_time, 8, 2));
				$date->setTimestamp(Jdf::jmktime(0, 0, 0, $month, $day, $year));
				$from = $date->format('Y-m-d');
			}
			else
			{
				$date = new \DateTime();
				$date->setTimezone(new \DateTimezone('Iran'));
				$begin_date = strtotime($begin_time);
				$date->setTimestamp($begin_date);
				$from = $date->format('Y-m-d');
			}
		}
		
		return $from;
	}
	
	private function getEndDate($lang, $end_time = null)
	{		
		if( $end_time == null)
		{
			if($lang == 'fa-IR')
			{
				$date=new \DateTime();
				$now_date = Jdf::jdate('Y/m/d', time(), '', 'Iran', 'en');
				$year = (int)(substr($now_date, 0, 4));
				$month = (int)(substr($now_date, 5, 2));
				$day = (int)(substr($now_date, 8, 2));
				$date->setTimestamp(Jdf::jmktime(23, 59, 59, $month, $day, $year));
				$to = $date->format('Y-m-d');
			}else
			{
				$date = new \DateTime();
				$date->setTimestamp(time());
				$date->setTimezone(new \DateTimezone('Iran'));
				$to = $date->modify('+1 day')->format('Y-m-d');
			}
		}else
		{
			if($lang == 'fa-IR')
			{
				$date=new \DateTime();
				$date->setTimezone(new \DateTimezone('Iran'));
				$year = (int)(substr($end_time, 0, 4));
				$month = (int)(substr($end_time, 5, 2));
				$day = (int)(substr($end_time, 8, 2));
				$date->setTimestamp(Jdf::jmktime(20, 29, 59, $month, $day, $year)); // TODO: fix with iran timezone later, PHP 7 jdf conflict
				$to = $date->format('Y-m-d');
			}else
			{
				$date = new \DateTime();
				$date->setTimezone(new \DateTimezone('Iran'));
				$end_date = strtotime($end_time);
				$date->setTimestamp($end_date);
				$to = $date->format('Y-m-d');
			}
			
		}
		return $to;
	}

    public function actionIndex($lang = 'fa-IR', $begin_time = null, $end_time = null)
    {
		Stat::setView('news', 'default', 'index');
		
		$this->layout = '//creative-item';
		Yii::$app->controller->addLanguageUrl('fa-IR', Yii::$app->urlManager->createUrl(['news', 'lang' => 'fa-IR']), (Yii::$app->controller->language !== 'fa-IR'));
		Yii::$app->controller->addLanguageUrl('en', Yii::$app->urlManager->createUrl(['news', 'lang' => 'en']), (Yii::$app->controller->language !== 'en'));
        
		$begin = $this->getBeginDate($this->language, $begin_time);
		$end = $this->getEndDate($this->language, $end_time); 
		$cat_list = [];
//		$news = news::find()->where(['between', 'creation_time', $begin, $end])->all();
		$cat = NewsCategory::find()->all();
		foreach($cat as $item)
		{
			$translation = NewsCategoryTranslation::findOne(array('cat_id' => $item->id, 'language' => $this->language));
			if($translation)
			{
				$cat_list[] = [
				    'id' => $item['id'],
                    'thumb' => NewsCategory::$upload_url . $item['id'] . '/' . $item['logo'],
                    'title' => $translation->title,
                ];
			}
		}
		return $this->render('index',[
			'category' => $cat_list,
			'from' => $begin,
			'to' => $end,
			'language' => $this->language
		]);

    }

    public function actionView($id, $lang = 'fa-IR')
    {
		Stat::setView('news', 'default', 'view');

        $this->layout = '//creative-item';
		Yii::$app->controller->addLanguageUrl('fa-IR', Yii::$app->urlManager->createUrl(['news/cat/view', 'id'=>$id, 'lang' => 'fa-IR']), (Yii::$app->controller->language !== 'fa-IR'));
		Yii::$app->controller->addLanguageUrl('en', Yii::$app->urlManager->createUrl(['news/cat/view', 'id'=>$id, 'lang' => 'en']), (Yii::$app->controller->language !== 'en'));

        $model = NewsCategory::findOne($id);
        $news_list = [];
        $cat_news = News::find()->where(['category_id' => $id])->all();
        foreach($cat_news as $item)
        {
            $translation = NewsTranslation::findOne(array('news_id' => $item->id, 'language' => $this->language));
            if($translation)
            {
                $news_list[] = [
                    'id' => $item['id'],
                    'thumb' => NewsCategory::$upload_url . $item['id'] . '/' . $item['thumb'],
                    'title' => $translation->title,
                    'subtitle' => $translation->subtitle,
                    'intro' => $translation->intro
                ];
            }
        }
        return $this->render('view', [
            'model' => $model,
            'news_list' => $news_list,
        ]);
    }
}
