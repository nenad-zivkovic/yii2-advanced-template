<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::t('app', 'Contact');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="col-md-5 well bs-component">

        <p>
            <?= Yii::t('app', 'If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.'); ?>
        </p>

        <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

            <?= $form->field($model, 'name')->textInput(
                ['placeholder' => Yii::t('app', 'Enter your name'), 'autofocus' => true]) ?>

            <?= $form->field($model, 'email')->input('email', ['placeholder' => Yii::t('app', 'Enter your e-mail')]) ?>

            <?= $form->field($model, 'subject')->textInput(['placeholder' => Yii::t('app', 'Enter the subject')]) ?>

            <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>

            <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'template' => 
                    '<div class="row">
                        <div class="col-lg-4">{image}</div>
                        <div class="col-lg-8">{input}</div>
                    </div>',
                'options' => ['placeholder' => Yii::t('app', 'Enter verification code'),'class' => 'form-control'],
                ]) 
            ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Submit'), 
                    ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
