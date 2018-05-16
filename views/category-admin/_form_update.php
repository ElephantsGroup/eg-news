<?php
use elephantsGroup\news\models\NewsCategory;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\NewsCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-category-form">

    <?php
        $base_module = Yii::$app->getModule('base');
        $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image_file')->label('')->fileInput() ?>

    <?= $form->field($model, 'status')->dropDownList(NewsCategory::getStatus(), ['prompt' => $base_module::t('Select Status ...')]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('news_cat', 'Create') : Yii::t('news_cat', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
