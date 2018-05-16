<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\NewsTranslation */

$this->title = Yii::t('news', 'Create News Translation');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('news', 'News Translations'), 'url' => ['index', 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('news', 'News Translations')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-translation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
