<?php
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Yii::$app->name) .' '. Yii::t('app', 'news');
$this->params['breadcrumbs'][] = Yii::t('app', 'Articles');
?>
<div class="article-index">

    <h1><?= Html::encode($this->title) ?> 
        <span class="small"> - <?= Yii::t('app', 'The best news available') ?></span> 
    </h1>

    <hr class="top">

    <?= ListView::widget([
        'summary' => false,
        'dataProvider' => $dataProvider,
        'emptyText' => Yii::t('app', 'We haven\'t created any articles yet.'),
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return $this->render('_index', ['model' => $model]);
        },
    ]) ?>

</div>
