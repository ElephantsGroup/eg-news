<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = Yii::t('news', 'Update News') . ' #' . $model->id . ' - ' . Yii::t('config', 'Company Name') . ' - ' . Yii::t('config', 'description');
$this->params['breadcrumbs'][] = ['label' => Yii::t('news', 'News'), 'url' => ['index', 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id, 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = Yii::t('news', 'Update News');
?>
<div class="news-update">

    <h1><?= Yii::t('news', 'Update News') . ' #' . $model->id ?></h1>

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
