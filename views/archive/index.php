<?php
use elephantsGroup\news\components\ArchivedNews;
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
    <?= ArchivedNews::widget() ?>
</div>
