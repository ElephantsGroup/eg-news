<?php
use elephantsGroup\news\models\NewsCategory;
use elephantsGroup\news\models\NewsTranslation;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\NewsCategoryTranslation */
/* @var $form yii\widgets\ActiveForm */
$module_base = \Yii::$app->getModule('base');

?>

<div class="news-category-translation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
		if(!$model->isNewRecord)
			echo $form->field($model, 'language')->dropDownList($module_base->languages, ['prompt' => Yii::t('app', 'Select Languages ...')]);
	?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('news_cat', 'Create') : Yii::t('news_cat', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
