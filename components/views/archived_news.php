<?php
	use yii\helpers\Url;
	use elephantsGroup\news\models\News;

?>

<section id="news" class="light-bg">
	<div class="container inner">
		<div class="row">
			<div class="col-md-8 col-sm-9 center-block text-center" style="float: right;">
				<header style="background-image: none; color: black;">
					<h2><?= $last_news_title; ?></h2>
					<p><?= $last_news_subtitle; ?></p>
				</header>
			</div><!-- /.col -->
		</div><!-- /.row -->
		<div class="row inner-top-sm">
<?php
	foreach($news as $news_item)
	{
		echo '<div class="col-md-4 inner-bottom-xs" style="padding-top: 30px; float: right">' .
			'<figure><img src="' . $news_item['thumb'] . '" alt="' . $news_item['title'] . '"></figure>' .
			'<h4>' . $news_item['subtitle'] . '</h4>' .
			'<h3>' . $news_item['title'] . '</h3>' .
			'<div class="text-small">' . $news_item['intro'] . '</div>' .
		'</div><!-- /.col -->';
	}
?>
		</div><!-- /.row -->		
	</div><!-- /.container -->
</section>