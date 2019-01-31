<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use elephantsGroup\news\models\News;
use elephantsGroup\news\models\NewsCategory;
use elephantsGroup\jdf\Jdf;
use elephantsGroup\user\models\User;
use Yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = Yii::t('news', 'News id') . ' ' . $model->id;
$translation = $model->translationByLang;
if($translation && $translation->title)
	$this->title = $translation->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('news', 'News'), 'url' => ['index', 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('news', 'Update'), ['update', 'id' => $model->id, 'lang'=>Yii::$app->controller->language], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('news', 'Delete'), ['delete', 'id' => $model->id, 'redirectUrl' => Url::to([ '/news/admin']), 'lang'=>Yii::$app->controller->language], [
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
			[
				'attribute'  => 'category_id',
				'value'  => NewsCategory::findOne($model->category_id)->name,
				//'filter' => Lookup::items('SubjectType'),
			],
			[
                'attribute' => 'thumb',
                'value' => News::$upload_url . $model->id . '/' . $model->thumb,
                'format' => ['image'],
            ],
			[
				'attribute'  => 'creation_time',
				'value'  => Jdf::jdate('Y/m/d H:i:s', (new \DateTime($model->creation_time))->getTimestamp(), '', 'Iran', 'en'),
				//'filter' => Lookup::items('SubjectType'),
			],
			[
				'attribute'  => 'update_time',
				'value'  => Jdf::jdate('Y/m/d H:i:s', (new \DateTime($model->update_time))->getTimestamp(), '', 'Iran', 'en'),
				//'filter' => Lookup::items('SubjectType'),
			],
			[
				'attribute'  => 'archive_time',
				'value'  => Jdf::jdate('Y/m/d H:i:s', (new \DateTime($model->archive_time))->getTimestamp(), '', 'Iran', 'en'),
				//'filter' => Lookup::items('SubjectType'),
			],
			[
				'attribute'  => 'publish_time',
				'value'  => Jdf::jdate('Y/m/d H:i:s', (new \DateTime($model->publish_time))->getTimestamp(), '', 'Iran', 'en'),
				//'filter' => Lookup::items('SubjectType'),
			],
    	'views',
			[
				'attribute'  => 'author_id',
				'value'  => User::findOne($model->author_id)->username,
				//'filter' => Lookup::items('SubjectType'),
			],
			[
				'attribute'  => 'status',
				'value'  => News::getStatus()[$model->status],
				//'filter' => Lookup::items('SubjectType'),
			],
        ],
    ]) ?>

</div>
