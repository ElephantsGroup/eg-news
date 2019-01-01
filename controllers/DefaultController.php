<?php

namespace elephantsGroup\news\controllers;

use Yii;
//use yii\web\Controller;
use elephantsGroup\news\models\News;
use elephantsGroup\news\models\NewsTranslation;
use elephantsGroup\stat\models\Stat;
use elephantsGroup\base\EGController;
use elephantsGroup\jdf\Jdf;

class DefaultController extends EGController
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

	        //$this->layout = '//creative-item';
			Yii::$app->controller->addLanguageUrl('fa-IR', Yii::$app->urlManager->createUrl(['news', 'lang' => 'fa-IR']), (Yii::$app->controller->language !== 'fa-IR'));
			Yii::$app->controller->addLanguageUrl('en', Yii::$app->urlManager->createUrl(['news', 'lang' => 'en']), (Yii::$app->controller->language !== 'en'));

			$begin = $this->getBeginDate($this->language, $begin_time);
			$end = $this->getEndDate($this->language, $end_time);
			$news_list = [];
	//		$news = News::find()->where(['between', 'creation_time', $begin, $end])->all();
			$news = News::find()->notEdited()->all();
			foreach($news as $news_item)
			{
				$max_version_translation = NewsTranslation::find()->where(['news_id' => $news_item->id, 'language' => $this->language])->max('version');
				$translation = NewsTranslation::findOne(array('news_id' => $news_item->id, 'language' => $this->language, 'version' => $max_version_translation));
				if($translation)
				{
					$news_list[] = [
					    'id' => $news_item['id'],
	                    'thumb' => News::$upload_url . $news_item['id'] . '/' . $news_item['thumb'],
	                    'title' => $translation->title,
	                    'subtitle' => $translation->subtitle,
	                    'intro' => $translation->intro
	                ];
				}
			}
			//var_dump($end); die;
			return $this->render('index',[
				'news' => $news_list,
				'from' => $begin,
				'to' => $end,
				'language' => $this->language
			]);
    }

    public function actionView($id, $lang = 'fa-IR')
    {
		Stat::setView('news', 'default', 'view');

        //$this->layout = '//creative-item';
		Yii::$app->controller->addLanguageUrl('fa-IR', Yii::$app->urlManager->createUrl(['news/default/view', 'id'=>$id, 'lang' => 'fa-IR']), (Yii::$app->controller->language !== 'fa-IR'));
		Yii::$app->controller->addLanguageUrl('en', Yii::$app->urlManager->createUrl(['news/default/view', 'id'=>$id, 'lang' => 'en']), (Yii::$app->controller->language !== 'en'));
		//$model = News::findOne($id);
		$max_version = News::find()->where(['id' => $id])->max('version');
		$model = News::findOne(['id' => $id, 'version' => $max_version]);

		if(!$model)
			throw new NotFoundHttpException('The requested page does not exist.');
		$model->views++;
		$model->save();
        return $this->render('view', [
            'model' => $model,
        ]);
    }
}
