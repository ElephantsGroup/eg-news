<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use elephantsGroup\news\models\News;
use elephantsGroup\news\models\NewsCategory;
use elephantsGroup\jdf\Jdf;
use elephantsGroup\user\models\User;
use elephantsGroup\news\models\NewsCategoryTranslation;
use yii\helpers\Url;
use elephantsGroup\follow\assets\FollowAsset;

FollowAsset::register($this);


/* @var $this yii\web\View */
/* @var $model app\models\News */
$module_cat = \Yii::$app->getModule('news_cat');
$module = \Yii::$app->getModule('news');
$lang = Yii::$app->language;

$this->params['breadcrumbs'][] = ['label' => Yii::t('news', 'News'), 'url' => ['index', 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- ============================================================= SECTION – News POST ============================================================= -->
<header>
	<div class="header-content">
		<div class="header-content-inner">
			<a href="<?= Yii::getAlias('@web') ?>/news-list/index" style="color: white"><h1 id="homeHeading"><?= Yii::t('news_cat', 'Related News Categories')?></h1></a>
			<hr>
			<p><?= ($model->translationByLang ? $model->translationByLang->title : '') ?></p>

		</div>
	</div>
</header>
<section id="news-post" class="light-bg">
	<div class="container inner-top-sm inner-bottom classic-news no-sidebar">
		<div class="row">
			<div class="col-md-9 center-block">
					
				<div class="post">
				
					<div class="post-content">
						<div class="post-media">
							<figure>
								<img src=" <?= NewsCategory::$upload_url . $model->id . '/' . $model->logo ?>" alt="">
							</figure>
							<?php
								if($module->enabled_follow) echo \elephantsGroup\follow\components\Follows::widget(['item' => $model->id, 'service' => 2]);
							?>
						</div>
						
						<h1 class="post-title"><?= Html::encode($this->title) ?></h1>

						<ul class="post-details">
							<?php foreach ($news_list as $item):?>
							<li>
								<h4><?= $item['subtitle'] ?></h4>
								<h3>
									<a href="<?= Url::to(['/news/default/view', 'id'=>$item['id']]) ?>"><?= $item['title'] ?></a>
								</h3>
								<div class="text-small"><?= $item['intro'] ?></div>
							</li>
							<?php endforeach;?>
						</ul><!-- /.post-details -->
						<div class="clearfix"></div>

					</div><!-- /.post-content -->
					
				</div><!-- /.post -->
				
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container -->
</section>

<!-- ============================================================= SECTION – News POST : END ============================================================= -->
