<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use elephantsGroup\news\models\NewsTranslation;

/* @var $this yii\web\View */
/* @var $model app\models\NewsTranslation */

$this->title = $model->title;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('news', 'News Translations'), 'url' => ['index', 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('news', 'News Translations')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-translation-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('news_cat', 'Update'), ['update', 'news_id' => $model->news_id, 'language' => $model->language, 'lang'=>Yii::$app->controller->language], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('news_cat', 'Delete'), ['delete', 'news_id' => $model->news_id, 'language' => $model->language, 'lang'=>Yii::$app->controller->language], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('news', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php 
	$module_base = \Yii::$app->getModule('base');
	echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'news_id',
			[
				'attribute'  => 'language',
                'value'  => $module_base->languages[$model->language],
				//'filter' => Lookup::items('SubjectType'),
			],
            'title',
            'subtitle',
            'intro:ntext',
            'description:ntext',
        ],
    ]) ?>

</div>
