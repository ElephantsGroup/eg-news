<?php

namespace elephantsGroup\news\controllers;

use Yii;
use elephantsGroup\news\models\NewsCategoryTranslation;
use elephantsGroup\news\models\NewsCategoryTranslationSearch;
//use yii\web\Controller;
use elephantsGroup\base\EGController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NewsCategoryTranslationController implements the CRUD actions for NewsCategoryTranslation model.
 */
class CategoryTranslationController extends EGController
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
		return $behaviors;

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
    }

    /**
     * Lists all NewsCategoryTranslation models.
     * @return mixed
     */
    /*public function actionIndex($lang = 'fa-IR')
    {
        $searchModel = new NewsCategoryTranslationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }*/

    /**
     * Displays a single NewsCategoryTranslation model.
     * @param integer $cat_id
     * @param string $language
     * @return mixed
     */
    public function actionView($cat_id, $language)
    {
        return $this->render('view', [
            'model' => $this->findModel($cat_id, $language),
        ]);
    }

    /**
     * Creates a new NewsCategoryTranslation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($cat_id, $language, $lang = 'fa-IR', $redirectUrl)
    {
        $model = new NewsCategoryTranslation();
    		$model->cat_id = $cat_id;
    		$model->language = $language;

        if ($model->load(Yii::$app->request->post()) && $model->save())
    		{
    			$model->cat_id = $cat_id;
    			$model->language = $language;
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
     * Updates an existing NewsCategoryTranslation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $cat_id
     * @param string $language
     * @return mixed
     */
    public function actionUpdate($cat_id, $language, $lang = 'fa-IR', $redirectUrl)
    {
        $model = $this->findModel($cat_id, $language);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect($redirectUrl);
        }
		else
		{
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing NewsCategoryTranslation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $cat_id
     * @param string $language
     * @return mixed
     */
    public function actionDelete($cat_id, $language, $lang = 'fa-IR', $redirectUrl)
    {
        $this->findModel($cat_id, $language)->delete();

		return $this->redirect($redirectUrl);
	}
    /**
     * Finds the NewsCategoryTranslation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $cat_id
     * @param string $language
     * @return NewsCategoryTranslation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($cat_id, $language)
    {
        if (($model = NewsCategoryTranslation::findOne(['cat_id' => $cat_id, 'language' => $language])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
