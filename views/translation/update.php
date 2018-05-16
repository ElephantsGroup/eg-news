<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NewsTranslation */

$this->title = Yii::t('news', 'Update News Translation') . ' : ' . $model->title;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('news', 'News Translations'), 'url' => ['index', 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('news', 'News Translations')];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'news_id' => $model->news_id, 'language' => $model->language, 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = Yii::t('news', 'Update');
?>
<div class="news-translation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
