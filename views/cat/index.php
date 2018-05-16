<?php
use yii\helpers\Url;
use elephantsGroup\blog\components\LastBlog;
use elephantsGroup\blog\components\DateList;
use elephantsGroup\blog\models\Blog;
$module = \Yii::$app->getModule('news_cat');
?>
<header>
	<div class="header-content">
		<div class="header-content-inner">
			<h1 id="homeHeading"><?= Yii::t('news_cat', 'News Category List')?></h1>
			<hr>
			<p><?= Yii::t('app', 'News Description')?></p>
		</div>
	</div>
</header>

<div class="news-default-index">
	<?php
//		echo LastBlog::widget(['title'=>Yii::t('blog', 'Blog'), 'subtitle'=>' ', 'show_archive_button'=>true, 'archive_button_text'=>Yii::t('blog', 'َBlog Archive')]);
		//echo DateList::widget();
	?>
	<section id="news" class="light-bg">
		<div class="container inner">
			<div class="row inner-top-sm">
				<?php foreach($category as $item): ?>
				<div class="col-md-8 inner-bottom-xs" style="padding-top: 30px; float: right" >
					<figure><img src=" <?= $item['thumb'] ?> " alt=" <?= $item['title'] ?>"></figure>
					<h3>
					<a href="<?= Url::to(['/news/cat/view', 'id'=>$item['id'], 'lang'=>$language]) ?>"><?= $item['title'] ?></a>
					</h3>
				</div>
				<?php endforeach;?>
			</div><!-- /.row -->
		</div><!-- /.container -->
	</section>
</div>

