<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Article */

$this->title = Yii::t('app', 'Update Article') . ': ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Articles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="article-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="col-lg-8 well bs-component">

        <?= $this->render('_form', ['model' => $model]) ?>

    </div>

</div>
