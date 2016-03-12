<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$this->title = Yii::t('app', 'Update User') . ': ' . Html::encode($user->username);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Html::encode($user->username), 'url' => ['view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="user-update">

    <h1><?= $this->title ?></h1>

    <div class="col-md-5 well bs-component">

        <?= $this->render('_form', ['user' => $user]) ?>

    </div>

</div>
