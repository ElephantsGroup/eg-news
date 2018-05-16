<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\NewsCategoryTranslation */

$this->title = Yii::t('news_cat', 'Create News Category Translation');
//$this->params['breadcrumbs'][] = ['label' => 'News Category Translations', 'url' => ['index', 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('news_cat', 'News Category Translations')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-category-translation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
