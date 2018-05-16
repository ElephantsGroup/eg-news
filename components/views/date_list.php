<?php
	use yii\helpers\Url;

?>

<section id="news" class="light-bg">
	<div class="container inner">
		<div class="row inner-top-sm">
<?php
	foreach($date as $date_item)
	{	
		echo '<div class="row inner-bottom-xs">' .
			//'<div class="text-small">' . $date_item['year'] . '-' . $date_item['month'] . '  (' . $date_item['count'] . ')</div>' .
			'<h3>' .
			'<a href="' . Url::to(['/news/default/index', 'lang'=>$language, 'begin_time'=>$date_item['from'], 'end_time'=>$date_item['to']]) . '">' . $date_item['year'] . '-' . $date_item['month'] . '  (' . $date_item['count'] . ')' . '</a>' .
			'</h3>' .
		'</div><!-- /.row -->';
	}
?>
		</div><!-- /.row -->
	</div><!-- /.container -->
</section>