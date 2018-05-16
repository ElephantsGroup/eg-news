<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use elephantsGroup\news\models\NewsCategory;
use elephantsGroup\news\models\NewsCategoryTranslation;

/* @var $this yii\web\View */
/* @var $model app\models\NewsCategory */

$this->title = $model->name;
$translation = $model->translationByLang;
if($translation && $translation->title)
	$this->title = $translation->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('news', 'News Categories'), 'url' => ['index', 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-category-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('news_cat', 'Update'), ['update', 'id' => $model->id, 'lang'=>Yii::$app->controller->language], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('news_cat', 'Delete'), ['delete', 'id' => $model->id, 'lang'=>Yii::$app->controller->language], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('news', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
			[
                'attribute' => 'logo',
                'value' => NewsCategory::$upload_url . $model->id . '/' . $model->logo,
                'format' => ['image'],
            ],
			[
                'attribute' => 'status',
                'value' => NewsCategory::getStatus()[$model->status],
                'format' => 'raw',
            ],
        ],
    ]) ?>

</div>
