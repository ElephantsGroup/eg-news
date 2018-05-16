<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NewsCategory */

$this->title = Yii::t('news', 'Update News Category') . ' ' . $model->name . ' - ' . Yii::t('config', 'Company Name') . ' - ' . Yii::t('config', 'description');
$this->params['breadcrumbs'][] = ['label' => Yii::t('news', 'News Categories'), 'url' => ['index', 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id, 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = Yii::t('news', 'Update');
?>
<div class="news-category-update">

    <h1><?= Yii::t('news', 'Update News Category') . ' ' . $model->name ?></h1>

    <?php
		if($translation)
			echo
				$this->render('_form_update_translate', [
					'model' => $model,
					'translation' => $translation,
				]);
		else
			echo
				$this->render('_form_update', [
					'model' => $model,
				]);
	?>

</div>
