<?php
	use yii\helpers\Url;
?>

<section id="news" class="light-bg">
	<div class="container inner">
		<div class="row">
			<div class="col-md-8 col-sm-9 center-block text-center">
				<header>
					<?php echo ($news_title_is_link ? '<a href="' . Url::to(['/news', 'lang'=>$language]) . '"><h2>' . $last_news_title . '</h2></a>' : '<h2>' . $last_news_title . '</h2>') ?>

					<?php if(!$last_news_subtitle)
						echo "<p> $last_news_subtitle </p>";
					?>
				</header>
			</div><!-- /.col -->
		</div><!-- /.row -->
		<div class="row inner-top-sm">
<?php
	foreach($news as $news_item)
	{	
		echo
			'<div class="col-md-4 inner-bottom-xs">' .
			'<figure><img src="' . $news_item['thumb'] . '" alt="' . $news_item['title'] . '"></figure>' .
			'<h4>' . $news_item['subtitle'] . '</h4>' .
			'<h3>' .
			($news_title_is_link ? '<a href="' . Url::to(['/news/default/view', 'id'=>$news_item['id'], 'lang'=>$language]) . '">' . $news_item['title'] . '</a>' : $news_item['title']) .
			'</h3>' .

			'<div class="text-small">' . $news_item['intro'] . '</div>' .
		'</div><!-- /.col -->';
	}
?>
		</div><!-- /.row -->
		<?php if($show_global_more)
			echo '<div class="row text-center"><a href="' . Yii::getAlias('@web') . '/news" class="btn btn-default">' . $global_more_text . '</a></div>';
		?>
		<?php if($show_archive_button)
			echo '<div class="row text-center"><a href="' . Yii::getAlias('@web') . '/news/archive" class="btn btn-default">' . $archive_button_text . '</a></div>';
		?>
	</div><!-- /.container -->
</section>