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
		$behaviors['verbs'] = [
			'class' => VerbFilter::className(),
			'actions' => [
				'delete' => ['post'],
			],
		];
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
            'model' => $this->findModel($id),
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
			$model->author_id = (int) Yii::$app->user->id;
			$model->image_file = UploadedFile::getInstance($model, 'image_file');

			if($model->save())
			{
				if ($translation->load(Yii::$app->request->post()))
				{
					$translation->news_id = $model->id;
					$translation->language = $this->language;
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

        $model = $this->findModel($id);
        $translation = NewsTranslation::findOne(array('news_id' => $id, 'language' => $this->language));

		date_default_timezone_set('Iran'); 

		$timestamp = (new \DateTime($model->archive_time))->getTimestamp();
		$hour = Jdf::jdate('h', $timestamp, '', 'Iran', 'en');
		$minute = Jdf::jdate('i', $timestamp, '', 'Iran', 'en');
		$second = Jdf::jdate('s', $timestamp, '', 'Iran', 'en');
		$type = 'AM';
		$model->archive_time_time = $hour . ':' . $minute . ':' . $second . ' ' . $type;
		$model->archive_time = Jdf::jdate('Y/m/d', $timestamp, '', 'Iran', 'en');

		if ($model->load(Yii::$app->request->post()))
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
			$model->author_id = (int) Yii::$app->user->id;

			$model->image_file = UploadedFile::getInstance($model, 'image_file');

			if($model->save())
			{
				if ($translation && $translation->load(Yii::$app->request->post()))
				{
					if(!$translation->title && !$translation->subtitle && !$translation->intro && !$translation->description)
						return $this->redirect(['view', 'id' => $model->id]);
					$translation->news_id = $model->id;
					$translation->language = $this->language;
					if($translation->save())
						return $this->redirect(['view', 'id' => $model->id]);					
				}
				return $this->redirect(['view', 'id' => $model->id]);					
			}
        }
		else
		{
            return $this->render('update', [
                'model' => $model,
				'translation' => $translation,
            ]);
        }
    }

    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
}
