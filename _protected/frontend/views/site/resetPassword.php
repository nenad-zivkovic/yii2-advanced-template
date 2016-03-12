<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\ResetPasswordForm */

use kartik\password\PasswordInput;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Reset password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="col-md-5 well bs-component">

        <p><?= Yii::t('app', 'Please choose your new password:') ?></p>

        <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

            <?= $form->field($model, 'password')->widget(PasswordInput::classname(), 
                ['options' => ['placeholder' => Yii::t('app', 'Enter new password'), 'autofocus' => true]]) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
            </div>
            
        <?php ActiveForm::end(); ?>

    </div>

</div>
