<?php

namespace elephantsGroup\news\controllers;

use Yii;
use elephantsGroup\news\models\News;
use elephantsGroup\news\models\NewsTranslation;
use elephantsGroup\news\models\NewsSearch;
//use yii\web\Controller;
//use app\modules\news\components\Controller;
use elephantsGroup\base\EGController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use elephantsGroup\jdf\Jdf;
use yii\web\UploadedFile;

/**
 * NewsController implements the CRUD actions for News model.
 */
class AdminController extends EGController
{
	public function behaviors()
	{
		$behaviors = [];
		/*$behaviors['verbs'] = [
			'class' => VerbFilter::className(),
			'actions' => [
				'delete' => ['post'],
			],
		];*/
		/*$auth = Yii::$app->getAuthManager();
		if ($auth)
		{
			$behaviors['access'] = [
				'class' => \yii\filters\AccessControl::className(),
				'only' => ['index', 'view', 'create', 'update', 'delete'],
				'rules' => [
					[
						'actions' => ['create'],
						'allow'   => true,
						'roles'   => ['news_publisher', 'admin'],
					],
					[
						'actions' => ['index', 'view', 'update'],
						'allow'   => true,
						'roles'   => ['news_editor', 'admin'],
					],
					[
						'actions' => ['index', 'view', 'create', 'update', 'delete'],
						'allow'   => true,
						'roles'   => ['news_admin', 'admin'],
					],
				],
			];
		}
		else
		{
			$behaviors['access'] = [
				'class' => \yii\filters\AccessControl::className(),
				'only' => ['index', 'view', 'create', 'update', 'delete'],
				'rules' => [
					[
						'actions' => ['index', 'view', 'create', 'update', 'delete'],
						'allow'   => true,
						'roles'   => ['@'],
					],
				],
			];
		}*/
		return $behaviors;
	}

	/**
	 * Lists all News models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new NewsSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single News model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModelMaxVersion($id),
		]);
	}

	/**
	 * Creates a new News model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$_SESSION['KCFINDER']['disabled'] = false;
		$_SESSION['KCFINDER']['uploadURL'] = News::$upload_url .'images/';
		$_SESSION['KCFINDER']['uploadDir'] = News::$upload_path . 'images/';

		$model = new News();
		$translation = new NewsTranslation();

		date_default_timezone_set('Iran');

		if ($model->load(Yii::$app->request->post()))
		{
			if($model->archive_time != null && !empty($model->archive_time))
			{
				$datetime = $model->archive_time;
				$time = $model->archive_time_time;
				$year = (int)(substr($datetime, 0, 4));
				$month = (int)(substr($datetime, 5, 2));
				$day = (int)(substr($datetime, 8, 2));
				$hour = (int)(substr($time, 0, 2));
				$minute = (int)(substr($time, 3, 2));
				$second = (int)(substr($time, 6, 2));
				if(substr($time, 9, 2) == 'PM')
					$hour += 12;
				$date = new \DateTime();
				$date->setTimestamp(Jdf::jmktime($hour, $minute, $second, $month, $day, $year));
				$model->archive_time = $date->format('Y-m-d H:i:s');
			}

			if($model->publish_time != null && !empty($model->publish_time))
			{
				$datetime_publish = $model->publish_time;
				$time_publish = $model->publish_time_time;
				$year_publish = (int)(substr($datetime_publish, 0, 4));
				$month_publish = (int)(substr($datetime_publish, 5, 2));
				$day_publish = (int)(substr($datetime_publish, 8, 2));
				$hour_publish = (int)(substr($time_publish, 0, 2));
				$minute_publish = (int)(substr($time_publish, 3, 2));
				$second_publish = (int)(substr($time_publish, 6, 2));
				if(substr($time_publish, 9, 2) == 'PM')
						$hour_publish += 12;
				$date_publish = new \DateTime();
				$date_publish->setTimestamp(Jdf::jmktime($hour_publish, $minute_publish, $second_publish, $month_publish, $day_publish, $year_publish));
				$model->publish_time = $date_publish->format('Y-m-d H:i:s');
			}

			$max_ID = News::find()->max('id');
			if($max_ID == null && empty($max_ID))
				$model->id = 1;
			else
				$model->id = $max_ID+1;
			$model->version = 1;
			$model->author_id = (int) Yii::$app->user->id;
			$model->image_file = UploadedFile::getInstance($model, 'image_file');

			if($model->save())
			{
				if ($translation->load(Yii::$app->request->post()))
				{
					$translation->news_id = $model->id;
					$translation->version = $model->version;
					$translation->language = $this->language;
					$translation->title = trim($translation->title);
					$translation->subtitle = trim($translation->subtitle);
					$translation->intro = trim($translation->intro);
					$translation->description = trim($translation->description);

					if($translation->save())
						return $this->redirect(['view', 'id' => $model->id]);
				}
			}
		}
		else
		{
			return $this->render('create',[
				'model' => $model,
				'translation' => $translation,
			]);
		}
	}

	/**
	 * Updates an existing News model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$_SESSION['KCFINDER']['disabled'] = false;
		$_SESSION['KCFINDER']['uploadURL'] = News::$upload_url .'images/';
		$_SESSION['KCFINDER']['uploadDir'] = News::$upload_path . 'images/';

		$max_version = News::find()->where(['id' => $id])->max('version');
		$news_previous_version = News::findOne(array('id' => $id, 'version' => $max_version));
		//var_dump($news_previous_version->attributes); die;
		$model = new News();

		$max_version_translation = NewsTranslation::find()->where(['news_id' => $id, 'language' => $this->language])->max('version');
		$translation_previous_version = NewsTranslation::findOne(array('news_id' => $id, 'language' => $this->language, 'version' => $max_version_translation));
		$translation = new NewsTranslation();

		if ($model->load(Yii::$app->request->post()) && $translation->load(Yii::$app->request->post()))
		{
			$model->id = $id;
			$model->version = $max_version;
			$model->image_file = UploadedFile::getInstance($model, 'image_file');

			if($model->image_file == null || empty($model->image_file))
				$model->thumb = $news_previous_version->thumb;

      if($model->archive_time == $news_previous_version->archive_time)
			{
				$model->archive_time = $news_previous_version->archive_time;
			}
			elseif($model->archive_time != null && !empty($model->archive_time))
			{
				$datetime = $model->archive_time;
				$time = $model->archive_time_time;
				$year = (int)(substr($datetime, 0, 4));
				$month = (int)(substr($datetime, 5, 2));
				$day = (int)(substr($datetime, 8, 2));
				$hour = (int)(substr($time, 0, 2));
				$minute = (int)(substr($time, 3, 2));
				$second = (int)(substr($time, 6, 2));
				if(substr($time, 9, 2) == 'PM')
					$hour += 12;
				$date = new \DateTime();
				$date->setTimestamp(Jdf::jmktime($hour, $minute, $second, $month, $day, $year));
				$model->archive_time = $date->format('Y-m-d H:i:s');
			}

			if($model->publish_time == $news_previous_version->publish_time)
			{
				$model->publish_time = $news_previous_version->publish_time;
			}
			elseif($model->publish_time != null && !empty($model->publish_time))
			{
				$datetime = $model->publish_time;
				$time = $model->publish_time_time;
				$year = (int)(substr($datetime, 0, 4));
				$month = (int)(substr($datetime, 5, 2));
				$day = (int)(substr($datetime, 8, 2));
				$hour = (int)(substr($time, 0, 2));
				$minute = (int)(substr($time, 3, 2));
				$second = (int)(substr($time, 6, 2));
				if(substr($time, 9, 2) == 'PM')
					$hour += 12;
				$date = new \DateTime();
				$date->setTimestamp(Jdf::jmktime($hour, $minute, $second, $month, $day, $year));
				$model->publish_time = $date->format('Y-m-d H:i:s');
			}

			$translation->news_id = $model->id;
			$translation->language = $this->language;
			$translation->version = $max_version_translation;
			$translation->title = trim($translation->title);
			$translation->subtitle = trim($translation->subtitle);
			$translation->intro = trim($translation->intro);
			$translation->description = trim($translation->description);

			$news_changed = !($model->attributes == $news_previous_version->attributes);
			$translation_changed = !($translation->attributes == $translation_previous_version->attributes);

			if(!$news_changed && !$translation_changed)
			{
				return $this->redirect(['view', 'id' => $model->id]);
			}
			else
			{
				$model->version = $max_version + 1;
				if($model->save())
				{
					$news_previous_version->updateAttributes (['status' => News::$_STATUS_EDITED]) ;
					if($translation_changed)
					{
						if(!$translation->title && !$translation->subtitle && !$translation->intro && !$translation->description)
							return $this->redirect(['view', 'id' => $model->id]);
						$translation->version = $model->version;
						if($translation->save())
							return $this->redirect(['view', 'id' => $model->id]);
					}
					else
					{
						return $this->redirect(['view', 'id' => $model->id]);
					}
				}
				else {
					var_dump($model->errors); die;
				}
			}
		}
		else
		{
			return $this->render('update', [
				'model' => $news_previous_version,
				'translation' => $translation_previous_version,
			]);
		}
	}

	/**
	 * Deletes an existing News model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id, $redirectUrl)
	{
		$news_version = News::find()->select('version')->where(['id' => $id])->all();
		foreach($news_version as $version)
		{
			foreach($this->findModels($id, $version) as $model)
				$model->delete();
		}
		return $this->redirect($redirectUrl);
	}

	  public function actionConfirm($id, $redirectUrl)
	  {
	    $news_module = Yii::$app->getModule('news');
	    $response = [
				'status' => 500,
				'message' => $news_module::t('news', 'Server problem')
			];
			try
			{
				$news = $this->findModelMaxVersion($id);
				if (!$news)
				{
					$response = [
						'status' => 500,
						'message' => $news_module::t('news', 'News Not Found.')
					];
				}

				if ($news->confirm())
				{
					$response = [
						'status' => 200,
						'message' => $news_module::t('news', 'Successful')
					];
				}
				else
				{
					$response = [
						'status' => 500,
						'message' => $news_module::t('news', 'cant set to confirm')
					];
				}
			}
			catch (Exception $exp)
			{
				$response = [
					'status' => 500,
					'message' => $news_module::t('news', $exp)
				];
			}
		return $this->redirect($redirectUrl);
	  }

		public function actionReject($id, $redirectUrl)
	  {
	    $news_module = Yii::$app->getModule('news');
	    $response = [
				'status' => 500,
				'message' => $news_module::t('news', 'Server problem')
			];
			try
			{
				$news = $this->findModelMaxVersion($id);
				if (!$news)
				{
					$response = [
						'status' => 500,
						'message' => $news_module::t('news', 'News Not Found.')
					];
				}

				if ($news->reject())
				{
					//var_dump($id); die;
					$response = [
						'status' => 200,
						'message' => $news_module::t('news', 'Successful')
					];
				}
				else
				{
					$response = [
						'status' => 500,
						'message' => $news_module::t('news', 'cant set to reject')
					];
				}
			}
			catch (Exception $exp)
			{
				$response = [
					'status' => 500,
					'message' => $news_module::t('news', $exp)
				];
			}

			//return json_encode($response);
			return $this->redirect($redirectUrl);
	  }

		public function actionArchive($id, $redirectUrl)
		{
			$news_module = Yii::$app->getModule('news');
			$response = [
				'status' => 500,
				'message' => $news_module::t('news', 'Server problem')
			];
			try
			{
				$news = $this->findModelMaxVersion($id);
				if (!$news)
				{
					$response = [
						'status' => 500,
						'message' => $news_module::t('news', 'News Not Found.')
					];
				}

				if ($news->archive())
				{
					//var_dump($id); die;
					$response = [
						'status' => 200,
						'message' => $news_module::t('news', 'Successful')
					];
				}
				else
				{
					$response = [
						'status' => 500,
						'message' => $news_module::t('news', 'cant set to archive')
					];
				}
			}
			catch (Exception $exp)
			{
				$response = [
					'status' => 500,
					'message' => $news_module::t('news', $exp)
				];
			}

			//return json_encode($response);
			return $this->redirect($redirectUrl);
		}
	/**
	 * Finds the News model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return News the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = News::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

	protected function findModels($id, $version)
	{
		if (($model = News::find()->where(['id' => $id, 'version' => $version])->all()) !== null)
	{
			return $model;
		}
	else
	{
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

	protected function findModelMaxVersion($id)
	{
	  $max_version = News::find()->where(['id' => $id])->max('version');
	  if (($model = News::findOne(['id' => $id, 'version' => $max_version])) !== null)
	  {
			return $model;
	  }
	  else
	  {
		  throw new NotFoundHttpException('The requested page does not exist.');
	  }
	}
}
