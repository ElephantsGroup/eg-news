<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = Yii::t('news', 'Create News') . ' - ' . Yii::t('config', 'Company Name') . ' - ' . Yii::t('config', 'description');
$this->params['breadcrumbs'][] = ['label' => Yii::t('news', 'News'), 'url' => ['index', 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = Yii::t('news', 'Create News');
?>
<div class="news-create">

    <h1><?= Yii::t('news', 'Create News') ?></h1>

    <?= $this->render('_form_create', [
        'model' => $model,
		'translation' => $translation,
    ]) ?>

</div>
