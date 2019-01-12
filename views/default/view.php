<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use elephantsGroup\news\models\News;
use elephantsGroup\news\models\NewsCategory;
use elephantsGroup\jdf\Jdf;
use elephantsGroup\user\models\User;
use elephantsGroup\news\models\NewsCategoryTranslation;
use elephantsGroup\starRating\assets\RatingAsset;

RatingAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\models\News */
$module = \Yii::$app->getModule('news');
$module_relation = \Yii::$app->getModule('service-relation');
$service_id = array_keys($module_relation->services, 'News')[0];

$lang = Yii::$app->language;
$this->title = Yii::t('news', 'News id') . ' ' . $model->id;
$translation = $model->translationByLang;
if($translation && $translation->title)
	$this->title = $translation->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('news', 'News'), 'url' => ['index', 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- ============================================================= SECTION – BLOG POST ============================================================= -->
<header>
	<div class="header-content">
		<div class="header-content-inner">
			<a href="<?= Yii::getAlias('@web') ?>/news-list/index" style="color: white"><h1 id="homeHeading"><?= Yii::t('app', 'News List')?></h1></a>
			<hr>
			<p><?= $model['title'] ?></p>
		</div>
	</div>
</header>
<section id="blog-post" class="light-bg">
	<div class="container inner-top-sm inner-bottom classic-blog no-sidebar">
		<div class="row">
			<div class="col-md-9 center-block">

				<div class="post">

					<div class="post-content">
						<div class="post-media">
							<figure>
								<img src=" <?= News::$upload_url . $model->id . '/' . $model->thumb ?>" alt="">
							</figure>
						</div>

						<p class="subtitle">
							<?php
								echo ($model->translationByLang ? $model->translationByLang->subtitle : '');
							?>
						</p>
						<div class="row">
							<div>
								<?php
								if($module->enabled_like) echo \elephantsGroup\like\components\Likes::widget(['item' => $model->id, 'service' => $service_id]);
								?>
							</div>
							<div class="col-md-6" style="float: right">
								<?php
								if($module->enabled_rating) echo \elephantsGroup\starRating\components\Rate::widget(['item' => $model->id, 'service' => $service_id]);
								?>
							</div>
						</div>

						<h1 class="post-title"><?= Html::encode($this->title) ?></h1>

						<ul class="post-details">
							<li class="date">
							<?php
								if ($lang=='fa-IR')
									echo Jdf::jdate('d F Y - H:i', (new \DateTime($model->creation_time))->getTimestamp(), '', 'Iran', 'fa-IR');
								else
									echo date('M d, Y', strtotime($model->creation_time));

							?>
							</li>
							<li class="categories">
								<a href="#">
								<?php
									$cat = NewsCategory::findOne($model->category_id);
									echo ($cat->translationByLang ? $cat->translationByLang->title : $cat->name);
								?>
								</a>
							</li>
							<?php /* echo "<li class='views'>$model->views</li>"; */ ?>

						</ul><!-- /.post-details -->
						<div class="clearfix"></div>

						<?php
							echo ($model->translationByLang ? $model->translationByLang->description : '');
						?>
						<hr>
						<?php if($module->enabled_comment):?>
							<h4 style="padding: 20px;"><?= Yii::t('app', 'Users Comment') ?></h4>
							<div>
								<?php
								echo \elephantsGroup\comment\components\LastComments::widget(['item' => $model->id, 'service' => $service_id]);
								?>
							</div>
							<?php
							echo \elephantsGroup\comment\components\Comments::widget(['item' => $model->id, 'item_version' => $model->version, 'service' => $service_id]);
							?>
						<?php endif;?>

					</div><!-- /.post-content -->

				</div><!-- /.post -->

			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container -->
</section>

<!-- ============================================================= SECTION – BLOG POST : END ============================================================= -->
