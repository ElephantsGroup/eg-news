<?php
use elephantsGroup\news\models\NewsCategory;
use elephantsGroup\news\models\NewsCategoryTranslation;
use elephantsGroup\news\models\News;
use elephantsGroup\user\models\User;
use elephantsGroup\jDate;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use mihaildev\ckeditor\CKEditor;
use kartik\time\TimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'category_id')->dropDownList(
			ArrayHelper::map(
				NewsCategory::find()
					->select(['id', NewsCategoryTranslation::tableName() . '.title AS title'])
					->joinWith('translations')
					->where(['language' => Yii::$app->controller->language])
					->all(),
				'id',
				function($array, $key){ return NewsCategoryTranslation::findOne(['cat_id'=>$array->id, 'language'=>Yii::$app->controller->language])->title; }
			)
		)
	?>

    <?= $form->field($model, 'image_file')->label('')->fileInput() ?>

    <?= $form->field($model, 'creation_time')->widget(jDate\DatePicker::className()) ?>

    <?= $form->field($model, 'update_time')->widget(jDate\DatePicker::className()) ?>

    <?= $form->field($model, 'archive_time')->widget(jDate\DatePicker::className()) ?>

	<?= $form->field($model, 'archive_time_time')->label('')->widget(TimePicker::className(), ['value' => $model->archive_time_time, 'pluginOptions' => ['showSeconds' => true]]) ?>

    <?= $form->field($model, 'views')->textInput() ?>

    <?= $form->field($model, 'author_id')->dropDownList(ArrayHelper::map(User::find()->all(), 'id', 'username')) ?>

    <?php
    //$form->field($model, 'status')->dropDownList(News::getStatus(), ['prompt' => Yii::t('app', 'Select Status ...')])
    ?>

    <?= $form->field($translation, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($translation, 'subtitle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($translation, 'intro')->textInput(); ?>

    <?= $form->field($translation, 'description')->widget(CKEditor::className(),[
		'editorOptions' => [
			'preset' => 'basic', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
			'inline' => false, //по умолчанию false
			'filebrowserImageBrowseUrl' => Yii::getAlias('@web') . '/kcfinder/browse.php?type=images',
			'filebrowserImageUploadUrl' => Yii::getAlias('@web') . '/kcfinder/upload.php?type=images',
			'filebrowserBrowseUrl' => Yii::getAlias('@web') . '/kcfinder/browse.php?type=files',
			'filebrowserUploadUrl' => Yii::getAlias('@web') . '/kcfinder/upload.php?type=files',
		],
	]);
	?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('news', 'Create') : Yii::t('news', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
