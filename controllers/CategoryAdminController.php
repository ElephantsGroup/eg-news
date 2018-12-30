<?php

namespace elephantsGroup\news\controllers;

use Yii;
use elephantsGroup\news\models\NewsCategory;
use elephantsGroup\news\models\NewsCategoryTranslation;
use elephantsGroup\news\models\NewsCategorySearch;
//use yii\web\Controller;
//use app\modules\news\components\Controller;
use elephantsGroup\base\EGController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * NewsCategoryController implements the CRUD actions for NewsCategory model.
 */
class CategoryAdminController extends EGController
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
     * Lists all NewsCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NewsCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single NewsCategory model.
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
     * Creates a new NewsCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new NewsCategory();
        $translation = new NewsCategoryTranslation();

        if ($model->load(Yii::$app->request->post()))
		{
			$model->image_file = UploadedFile::getInstance($model, 'image_file');

			if($model->save())
			{
				if ($translation->load(Yii::$app->request->post()))
				{
					if(!$translation->title)
						return $this->redirect(['view', 'id' => $model->id]);
					$translation->cat_id = $model->id;
					$translation->language = $this->language;
					if($translation->save())
						return $this->redirect(['view', 'id' => $model->id]);
				}
			}
        }
		else
		{
            return $this->render('create', [
                'model' => $model,
				'translation' => $translation,
            ]);
        }
    }

    /**
     * Updates an existing NewsCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $translation = NewsCategoryTranslation::findOne(array('cat_id' => $id, 'language' => $this->language));

        if ($model->load(Yii::$app->request->post()))
		{
			$model->image_file = UploadedFile::getInstance($model, 'image_file');

			if($model->save())
			{
				if ($translation && $translation->load(Yii::$app->request->post()))
				{
					$translation->cat_id = $model->id;
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
     * Deletes an existing NewsCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $redirectUrl)
    {
        $this->findModel($id)->delete();

        return $this->redirect($redirectUrl);
    }

    /**
     * Finds the NewsCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return NewsCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NewsCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
