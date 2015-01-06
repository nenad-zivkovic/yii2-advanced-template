<?php
use mihaildev\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-form">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'summary')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'content')->widget(CKEditor::className(),
            ['editorOptions' => [ 'preset' => 'full', 'inline' => false]]); ?>

    <div class="row">
    <div class="col-lg-6">

        <?= $form->field($model, 'status')->dropDownList($model->statusList) ?>

        <?= $form->field($model, 'category')->dropDownList($model->categoryList) ?>

    </div>
    </div> 

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') 
            : Yii::t('app', 'Update'), ['class' => $model->isNewRecord 
            ? 'btn btn-success' : 'btn btn-primary']) ?>

        <?= Html::a(Yii::t('app', 'Cancel'), ['article/index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
