<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Setting */

$this->title = 'Update Setting: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="settings-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
