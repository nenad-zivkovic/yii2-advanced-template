<?php
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = 'Articles';
?>

    <h2>
        <a href=<?= Url::to(['article/view', 'id' => $model->id]) ?>><?= $model->title ?></a>
    </h2>

    <p class="time"><span class="glyphicon glyphicon-time"></span> 
        <?= Yii::t('app','Published on').' '.date('F j, Y, g:i a', $model->created_at) ?></p>

    <br>

    <p><?= $model->summary ?></p>

    <a class="btn btn-primary" href=<?= Url::to(['article/view', 'id' => $model->id]) ?>>
        <?= yii::t('app','Read more'); ?><span class="glyphicon glyphicon-chevron-right"></span>
    </a>

    <hr class="article-devider">
