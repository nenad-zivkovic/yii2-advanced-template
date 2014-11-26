<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $role common\rbac\models\Role; */

$this->title = Yii::t('app', 'Update {modelClass}: ', ['modelClass' => 'User']) . ' ' . $user->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $user->username, 'url' => ['view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div id="form">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="user-update col-lg-5">
    <div class="well bs-component">

        <?= $this->render('_form', [
            'user' => $user,
            'role' => $role,
        ]) ?>

    </div>
    </div>

</div>
