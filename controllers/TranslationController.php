<?php

namespace elephantsGroup\news\controllers;

use Yii;
use elephantsGroup\news\models\NewsTranslation;
use elephantsGroup\news\models\News;
use elephantsGroup\news\models\NewsTranslationSearch;
//use yii\web\Controller;
use elephantsGroup\base\EGController;
use elephantsGroup\jdf\Jdf;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NewsTranslationController implements the CRUD actions for NewsTranslation model.
 */
class TranslationController extends EGController
{
    public function behaviors()
    {
		$behaviors = [];
		/*$behaviors['verbs'] = [
			'class' => VerbFilter::className(),
			'actions' => [
				'delete' => ['post'],
			],
		];
        $auth = Yii::$app->getAuthManager();
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
     * Lists all NewsTranslation models.
     * @return mixed
     */
    /*public function actionIndex($lang = 'fa-IR')
    {
        $searchModel = new NewsTranslationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }*/

    /**
     * Displays a single NewsTranslation model.
     * @param integer $news_id
     * @param string $language
     * @return mixed
     */
    public function actionView($news_id, $language)
    {
        return $this->render('view', [
            'model' => $this->findModel($news_id, $language),
        ]);
    }

    /**
     * Creates a new NewsTranslation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($news_id, $language, $lang = 'fa-IR', $redirectUrl)
    {
      $_SESSION['KCFINDER']['disabled'] = false;
      $_SESSION['KCFINDER']['uploadURL'] = News::$upload_url .'images/';
      $_SESSION['KCFINDER']['uploadDir'] = News::$upload_path . 'images/';

      $max_version = News::find()->where(['id' => $news_id])->max('version');
      $model = new NewsTranslation();
      $model->news_id = $news_id;
      $model->language = $language;
      $model->version = $max_version;

      if ($model->load(Yii::$app->request->post()))
      {
        $model->title = trim($model->title);
        $model->subtitle = trim($model->subtitle);
        $model->intro = trim($model->intro);
        $model->description = trim($model->description);

        if($model->save())
        	return $this->redirect($redirectUrl);
        }
      else
      {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing NewsTranslation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $news_id
     * @param string $language
     * @return mixed
     */
    public function actionUpdate($news_id, $language, $lang = 'fa-IR', $redirectUrl)
    {
		$_SESSION['KCFINDER']['disabled'] = false;
		$_SESSION['KCFINDER']['uploadURL'] = News::$upload_url .'images/';
		$_SESSION['KCFINDER']['uploadDir'] = News::$upload_path . 'images/';

		$max_version_translation = NewsTranslation::find()->where(['news_id' => $news_id, 'language' => $language])->max('version');
        $previous = $this->findModel($news_id, $max_version_translation, $language);
        $model = $this->findModel($news_id, $max_version_translation, $language);

		$max_version = News::find()->where(['id' => $news_id])->max('version');
		$news_previous_version = News::findOne(array('id' => $news_id, 'version' => $max_version));
        $news = new News();
        $new_translation = new NewsTranslation();

		if ($model->load(Yii::$app->request->post()) && $new_translation->load(Yii::$app->request->post()) && $model->validate())
		{
			$news->attributes = $news_previous_version->attributes;
			$news->version = $max_version + 1;

			$datetime = $news->archive_time;
			$time = $news->archive_time_time;
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
			$news->archive_time = $date->format('Y-m-d H:i:s');

			$new_translation->news_id = $model->news_id;
			$new_translation->language = $model->language;
			$new_translation->version = $max_version_translation;
			$new_translation->title = trim($new_translation->title);
			$new_translation->subtitle = trim($new_translation->subtitle);
			$new_translation->intro = trim($new_translation->intro);
			$new_translation->description = trim($new_translation->description);

			$translation_changed = !($new_translation->attributes == $previous->attributes);

			if(!$translation_changed)
			{
				return $this->redirect($redirectUrl);
			}
			else{
				if( $news->save())
				{
					$news_previous_version->updateAttributes (['status' => News::$_STATUS_EDITED]) ;
					$new_translation->version = $news->version;
					if ($new_translation->save())
					{
						return $this->redirect($redirectUrl);
					}
					else
					{
						return $this->render('update', [
							'model' => $model,
						]);
					}
				}
				else
					var_dump($news->errors); die;
			}
		}
		else
		{
			return $this->render('update', [
				'model' => $model,
			]);
		}
    }

    /**
     * Deletes an existing NewsTranslation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $news_id
     * @param string $language
     * @return mixed
     */
    public function actionDelete($news_id, $language, $lang = 'fa-IR',$redirectUrl)
    {
		$version = NewsTranslation::find()->where(['news_id' => $news_id, 'language' => $language])->max('version');
        $model = $this->findModel($news_id, $version, $language);

		if ($model->delete())
			return $this->redirect($redirectUrl);
		else
			var_dump($model->errors);
	}

    /**
     * Finds the NewsTranslation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $news_id
     * @param string $language
     * @return NewsTranslation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($news_id, $version, $language)
    {
        if (($model = NewsTranslation::findOne(['news_id' => $news_id, 'version' => $version, 'language' => $language])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
