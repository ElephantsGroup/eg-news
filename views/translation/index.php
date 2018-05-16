<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NewsTranslationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'News Translations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-translation-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create News Translation', ['create', 'lang'=>Yii::$app->controller->language], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'news_id',
            'language',
            'title',
            'subtitle',
            //'intro:ntext',
            // 'description:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
