<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model common\models\Setting */
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>
<hr>

<div class="settings-form">

    <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-lg-6">

                <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

             </div>
        </div>

        <div class="row">
            <div class="col-lg-2">

                <?= $form->field($model, 'value')->dropDownList(['0' => 'No', '1' => 'Yes']) ?>

            </div>
        </div>

        <div class="form-group">

            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', 
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

            <?= Html::a('Go back', ['setting/index'], ['class' => 'btn btn-warning']) ?>
            
        </div>

        <?php ActiveForm::end(); ?>

</div>
