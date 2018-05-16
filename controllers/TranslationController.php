<?php

namespace elephantsGroup\news\controllers;

use Yii;
use elephantsGroup\news\models\NewsTranslation;
use elephantsGroup\news\models\News;
use elephantsGroup\news\models\NewsTranslationSearch;
//use yii\web\Controller;
use elephantsGroup\base\EGController;
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
    public function actionCreate($news_id, $language, $lang = 'fa-IR')
    {
		$_SESSION['KCFINDER']['disabled'] = false;
		$_SESSION['KCFINDER']['uploadURL'] = News::$upload_url .'images/';
		$_SESSION['KCFINDER']['uploadDir'] = News::$upload_path . 'images/';

        $model = new NewsTranslation();
		$model->news_id = $news_id;
		$model->language = $language;

        if ($model->load(Yii::$app->request->post()) && $model->save())
		{
			$model->news_id = $news_id;
			$model->language = $language;
            return $this->redirect(['admin/index', 'lang' => $lang]);
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
    public function actionUpdate($news_id, $language, $lang = 'fa-IR')
    {
		$_SESSION['KCFINDER']['disabled'] = false;
		$_SESSION['KCFINDER']['uploadURL'] = News::$upload_url .'images/';
		$_SESSION['KCFINDER']['uploadDir'] = News::$upload_path . 'images/';

        $model = $this->findModel($news_id, $language);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['admin/index', 'lang' => $lang]);
        } else {
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
    public function actionDelete($news_id, $language, $lang = 'fa-IR')
    {
        $model = $this->findModel($news_id, $language);
		
		if ($model->delete())
			return $this->redirect(['admin/index', 'lang' => $lang]);
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
    protected function findModel($news_id, $language)
    {
        if (($model = NewsTranslation::findOne(['news_id' => $news_id, 'language' => $language])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
