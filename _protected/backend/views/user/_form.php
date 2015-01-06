<?php
use common\rbac\models\AuthItem;
use nenad\passwordStrength\PasswordInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $form yii\widgets\ActiveForm */
/* @var $role common\rbac\models\Role; */
?>
<div class="user-form">

    <?php $form = ActiveForm::begin(['id' => 'form-user']); ?>

        <?= $form->field($user, 'username') ?>
        
        <?= $form->field($user, 'email') ?>

        <?php if ($user->scenario === 'create'): ?>
            <?= $form->field($user, 'password')->widget(PasswordInput::classname(), []) ?>
        <?php else: ?>
            <?= $form->field($user, 'password')->widget(PasswordInput::classname(), [])
                     ->passwordInput(['placeholder' => Yii::t('app', 'New pwd ( if you want to change it )')]) 
            ?>       
        <?php endif ?>

    <div class="row">
    <div class="col-lg-6">

        <?= $form->field($user, 'status')->dropDownList($user->statusList) ?>

        <?php foreach (AuthItem::getRoles() as $item_name): ?>
            <?php $roles[$item_name->name] = $item_name->name ?>
        <?php endforeach ?>
        <?= $form->field($role, 'item_name')->dropDownList($roles) ?>

    </div>
    </div>

    <div class="form-group">     
        <?= Html::submitButton($user->isNewRecord ? Yii::t('app', 'Create') 
            : Yii::t('app', 'Update'), ['class' => $user->isNewRecord 
            ? 'btn btn-success' : 'btn btn-primary']) ?>

        <?= Html::a(Yii::t('app', 'Cancel'), ['user/index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
 
</div>
