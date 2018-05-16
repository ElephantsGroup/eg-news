<?php

use yii\helpers\Html;
use yii\grid\GridView;
use elephantsGroup\news\models\News;
use elephantsGroup\news\models\NewsTranslation;
use elephantsGroup\news\models\NewsCategory;
use elephantsGroup\news\models\NewsCategoryTranslation;
use elephantsGroup\jdf\Jdf;
use elephantsGroup\user\models\User;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel elephantsGroup\news\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$module = \Yii::$app->getModule('news');
$this->title = $module::t('news', 'News List') . ' - ' . $module::t('config', 'Company Name') . ' - ' . $module::t('config', 'description');
$this->params['breadcrumbs'][] = $module::t('news', 'News List');
?>
<div class="news-index">

    <h1><?= $module::t('news', 'News List') ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a($module::t('news', 'Create News'), ['create', 'lang'=>Yii::$app->controller->language], ['class' => 'btn btn-success']) ?>
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
				NewsTranslation::findOne(['news_id'=>$model->id, 'language'=>$item])
					? Html::a($module::t('news', 'Edit'), ['/news/translation/update', 'news_id'=>$model->id, 'language'=>$item, 'lang'=>Yii::$app->controller->language]) .
					' / ' . Html::a(Yii::t('news', 'Delete'), ['/news/translation/delete', 'news_id'=>$model->id, 'language'=>$item, 'lang'=>Yii::$app->controller->language])
					: Html::a($module::t('news', 'Create'), ['/news/translation/create', 'news_id'=>$model->id, 'language'=>$item, 'lang'=>Yii::$app->controller->language])
				);
			},
		];
	}

	$columns = [
		['class' => 'yii\grid\SerialColumn'],

		'id',
		[
			'attribute' => 'category_id',
			'format' => 'raw',
			'filter' => ArrayHelper::map(
				NewsCategory::find()
					->select(['id', NewsCategoryTranslation::tableName() . '.title AS title'])
					->joinWith('translations')
					->where(['language' => Yii::$app->controller->language])
					->all(),
				'id',
				function($array, $key){ return NewsCategoryTranslation::findOne(['cat_id'=>$array->id, 'language'=>Yii::$app->controller->language])->title; }
			),
			//'label' => Yii::t('user', 'Role'),
			//'sortable' => true,
			'value' => function ($model) {
				$cat = NewsCategory::findOne($model->category_id);
				$value = $cat->name;
				$translate = NewsCategoryTranslation::findOne(['cat_id'=>$model->category_id, 'language'=>Yii::$app->language]);
				if($translate)
					$value = $translate->title;
				return $value;
			},
		],
		/*[
            'label' => Yii::t('news', 'Title'),
            'format' => 'raw',
            //'label' => Yii::t('user', 'Role'),
            //'filter' => $role::dropdown(),
            //'sortable' => true,
            'value' => function ($model) {
                $value = Yii::t('news', 'Undefined');
                $translate = NewsTranslation::findOne(['news_id'=>$model->id, 'language'=>Yii::$app->language]);
                if($translate)
                    $value = $translate->title;
                return $value;
            },
        ],*/
		'title',
		'views',
		[
			'attribute' => 'author_id',
			'format' => 'raw',
			'filter' => ArrayHelper::map(User::find()->all(), 'id', 'username'),
			//'label' => Yii::t('user', 'Role'),
			//'sortable' => true,
			'value' => function ($model) { return User::findOne($model->author_id)->username; },
		],
		[
			'attribute' => 'status',
			'format' => 'raw',
			'filter' => News::getStatus(),
			//'label' => Yii::t('user', 'Role'),
			//'sortable' => true,
			'value' => function ($model) { return News::getStatus()[$model->status]; },
		],
		/*[
            'attribute' => 'creation_time',
            'format' => 'raw',
            //'label' => Yii::t('user', 'Role'),
            //'filter' => $role::dropdown(),
            //'sortable' => true,
            'value' => function ($model) { return Jdf::jdate('Y/m/d', (new \DateTime($model->creation_time))->getTimestamp(), '', 'Asia/Tehran', 'en'); },
        ],
        [
            'attribute' => 'update_time',
            'format' => 'raw',
            //'label' => Yii::t('user', 'Role'),
            //'filter' => $role::dropdown(),
            //'sortable' => true,
            'value' => function ($model) { return Jdf::jdate('Y/m/d', (new \DateTime($model->update_time))->getTimestamp(), '', 'Asia/Tehran', 'en'); },
        ],
        [
            'attribute' => 'archive_time',
            'format' => 'raw',
            //'label' => Yii::t('user', 'Role'),
            //'filter' => $role::dropdown(),
            //'sortable' => true,
            'value' => function ($model) { return Jdf::jdate('Y/m/d', (new \DateTime($model->archive_time))->getTimestamp(), '', 'Asia/Tehran', 'en'); },
        ],*/

		[
			'class' => 'yii\grid\ActionColumn',
			//'template' => '{view} {update} {delete}',
			'buttons' => [
				'view' => function ($url, $model)
				{
					$label = '<span class="glyphicon glyphicon-eye-open"></span>';
					$url = ['/news/admin/view', 'id'=>$model->id, 'lang'=>Yii::$app->controller->language];
					return Html::a($label, $url);
				},
				'update' => function ($url, $model)
				{
					$label = '<span class="glyphicon glyphicon-pencil"></span>';
					$url = ['/news/admin/update', 'id'=>$model->id, 'lang'=>Yii::$app->controller->language];
					return Html::a($label, $url);
				},
				'delete' => function ($url, $model)
				{
					$label = '<span class="glyphicon glyphicon-trash"></span>';
					$url = ['/news/admin/delete', 'id'=>$model->id, 'lang'=>Yii::$app->controller->language];
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

	array_splice($columns, 7, 0, $columns_d);
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>

</div>
