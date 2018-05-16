<?php
use yii\helpers\Url;
use elephantsGroup\news\components\LastNews;
use elephantsGroup\news\components\DateList;
$module = \Yii::$app->getModule('news');
?>
<header>
	<div class="header-content">
		<div class="header-content-inner">
			<h1 id="homeHeading"><?= Yii::t('app', 'News List')?></h1>
			<hr>
			<p><?= Yii::t('app', 'News Description')?></p>
		</div>
	</div>
</header>

<div class="news-default-index">
	<?php 
//		echo LastNews::widget(['title'=>Yii::t('app', 'News'), 'subtitle'=>' ', 'show_archive_button'=>true, 'archive_button_text'=>Yii::t('app', 'َNews Archive')]);
//		echo DateList::widget();
	?>
	<section id="news" class="light-bg">
		<div class="container inner">
			<div class="row inner-top-sm">
			<?php foreach($news as $news_item): ?>
				<div class="col-md-8 inner-bottom-xs" style="padding-top: 30px; float: right">
					<figure><img src="<?= $news_item['thumb'] ?>" alt="<?= $news_item['title'] ?>"></figure>
					<h4><?= $news_item['subtitle'] ?></h4>
					<?php
						if($module->enabled_like) echo \elephantsGroup\like\components\Likes::widget(['item' => $news_item['id'], 'service' => 2]);
					?>
					<h3>
					<a href="<?= Url::to(['/news/default/view', 'id' => $news_item['id'], 'lang'=>$language]) ?>"><?= $news_item['title'] ?></a>
					</h3>
					<div class="text-small"><?= $news_item['intro'] ?></div>
					<div class="col-md-4" style="float: right; padding: 20px;" >
					<?php
						if ($module->enabled_rating) echo \elephantsGroup\starRating\components\Rate::widget(['item' => $news_item['id'], 'service' => 2]);
					?>
					</div>
				</div>
			<?php endforeach;?>
			</div><!-- /.row -->
		</div><!-- /.container -->
	</section>
</div>

