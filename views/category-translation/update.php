<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NewsCategoryTranslation */

$this->title = Yii::t('news_cat', 'Update News Category Translation') . ' : ' . $model->title;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('news_cat', 'News Category Translations'), 'url' => ['index', 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('news_cat', 'News Category Translations')];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'cat_id' => $model->cat_id, 'language' => $model->language, 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = ['label' => $model->title];
$this->params['breadcrumbs'][] = Yii::t('news_cat', 'Update');
?>
<div class="news-category-translation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
