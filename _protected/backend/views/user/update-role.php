<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\rbac\models\AuthItem;

/* @var $this yii\web\View */
/* @var $model common\rbac\models\Role */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Update role for user: ' . $model->username;

$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['user/index']];
$this->params['breadcrumbs'][] = 'Update role';
?>
<div class="role-update">
    <h1><?= Html::encode($this->title) ?></h1>
<hr>

    <div class="role-form">

        <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-lg-3">

                    <?php foreach (AuthItem::getRoles() as $role): ?>
                        <?php $roles[$role->name] = $role->name ?>
                    <?php endforeach ?>

                    <?= $form->field($model, 'item_name')->dropDownList($roles) ?>

                </div>
            </div>

        <div class="form-group">
            <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>

            <?= Html::a('Go back', ['user/index'], ['class' => 'btn btn-warning']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
