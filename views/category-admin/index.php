<?php

use yii\helpers\Html;
use yii\grid\GridView;
use elephantsGroup\news\models\NewsCategory;
use elephantsGroup\news\models\NewsCategoryTranslation;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NewsCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('news', 'News Categories') . ' - ' . Yii::t('config', 'Company Name') . ' - ' . Yii::t('config', 'description');
$this->params['breadcrumbs'][] = Yii::t('news', 'News Categories');
?>
<div class="news-category-index">

    <h1><?= Yii::t('news', 'News Categories') ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('news', 'Create News Category'), ['create', 'lang'=>Yii::$app->controller->language], ['class' => 'btn btn-success']) ?>
    </p>

	<?php
	$module = \Yii::$app->getModule('news');
	$module_base = \Yii::$app->getModule('base');
	$columns_d = [];
	$language = array_keys($module_base->languages);

	foreach ($language as $item)
	{
		$columns_d [] = [
			'format' => 'raw',
			'label' => $module_base::t($item, 'coding'),
			'value' => function ($model) use($module, $module_base, $item)  {
				return (
				NewsCategoryTranslation::findOne(['cat_id'=>$model->id, 'language'=>$item])
					? Html::a(Yii::t('news', 'Edit'), ['/news/category-translation/update', 'cat_id'=>$model->id, 'language'=>$item, 'lang'=>Yii::$app->controller->language, 'redirectUrl'=>Yii::$app->request->url]) .
					' / ' . Html::a(Yii::t('news', 'Delete'), ['/news/category-translation/delete', 'cat_id'=>$model->id, 'language'=>$item, 'lang'=>Yii::$app->controller->language, 'redirectUrl'=>Yii::$app->request->url])
					: Html::a(Yii::t('news', 'Create'), ['/news/category-translation/create', 'cat_id'=>$model->id, 'language'=>$item, 'lang'=>Yii::$app->controller->language, 'redirectUrl'=>Yii::$app->request->url])
				);
			},
		];
	}
	$columns = [
		['class' => 'yii\grid\SerialColumn'],

		'id',
		'name',
		'title',
		[
			'attribute' => 'status',
			'format' => 'raw',
			//'label' => Yii::t('user', 'Role'),
			'filter' => NewsCategory::getStatus(),
			//'sortable' => true,
			'value' => function ($model) { return NewsCategory::getStatus()[$model->status]; },
		],
		//'logo',
		/*[
            'label' => Yii::t('news', 'Title'),
            //'format' => 'raw',
            'filter' => NewsCategoryTranslation::find(),
            'value' => function ($model) {
                $value = Yii::t('news', 'Undefined');
                $translate = NewsCategoryTranslation::findOne(['cat_id'=>$model->id, 'language'=>Yii::$app->language]);
                if($translate)
                    $value = $translate->title;
                return $value;
            },
        ],*/

		[
			'class' => 'yii\grid\ActionColumn',
			//'template' => '{view} {update} {delete}',
			'buttons' => [
				'view' => function ($url, $model)
				{
					$label = '<span class="glyphicon glyphicon-eye-open"></span>';
					$url = ['/news/category-admin/view', 'id'=>$model->id, 'lang'=>Yii::$app->controller->language];
					return Html::a($label, $url);
				},
				'update' => function ($url, $model)
				{
					$label = '<span class="glyphicon glyphicon-pencil"></span>';
					$url = ['/news/category-admin/update', 'id'=>$model->id, 'lang'=>Yii::$app->controller->language];
					return Html::a($label, $url);
				},
				'delete' => function ($url, $model)
				{
					$label = '<span class="glyphicon glyphicon-trash"></span>';
					$url = ['/news/category-admin/delete', 'id'=>$model->id, 'lang'=>Yii::$app->controller->language, 'redirectUrl'=>Yii::$app->request->url];
					$options = [
						'title' => Yii::t('yii', 'Delete'),
						'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
						'data-method' => 'post'
					];
					return Html::a($label, $url, $options);
				},
			],
		],
	];

	array_splice($columns, 5, 0, $columns_d);

	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>

</div>
