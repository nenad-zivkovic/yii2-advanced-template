<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$this->title = Html::encode(Yii::t('app', 'Create User'));
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= $this->title ?></h1>

    <div class="col-md-5 well bs-component">

        <?= $this->render('_form', ['user' => $user]) ?>

    </div>

</div>

