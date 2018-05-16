<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\NewsCategory */

$this->title = Yii::t('news', 'Create News Category') . ' - ' . Yii::t('config', 'Company Name') . ' - ' . Yii::t('config', 'description');;
$this->params['breadcrumbs'][] = ['label' => Yii::t('news', 'News Categories'), 'url' => ['index', 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = Yii::t('news', 'Create News Category');
?>
<div class="news-category-create">

    <h1><?= Yii::t('news', 'Create News Category') ?></h1>

    <?= $this->render('_form_create', [
        'model' => $model,
		'translation' => $translation,
    ]) ?>

</div>
