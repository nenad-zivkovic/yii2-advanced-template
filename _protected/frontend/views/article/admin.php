<?php
use common\helpers\CssHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Articles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="articles-admin">

    <h1>

    <?= Html::encode($this->title) ?>

    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create Article'), ['create'], ['class' => 'btn btn-success']) ?>
    </span>  

    </h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            // author
            [
                'attribute'=>'user_id',
                'value' => function ($data) {
                    return $data->getAuthorName();
                },
            ],
            'title',
            // status
            [
                'attribute'=>'status',
                'filter' => $searchModel->statusList,
                'value' => function ($data) {
                    return $data->getStatusName($data->status);
                },
                'contentOptions'=>function($model, $key, $index, $column) {
                    return ['class'=>CssHelper::articleStatusCss($model->statusName)];
                }
            ],
            [
                'attribute'=>'category',
                'filter' => $searchModel->categoryList,
                'value' => function ($data) {
                    return $data->getCategoryName($data->category);
                },
                'contentOptions'=>function($model, $key, $index, $column) {
                    return ['class'=>CssHelper::articleCategoryCss($model->categoryName)];
                }
            ],

            ['class' => 'yii\grid\ActionColumn',
            'header' => Yii::t('app', 'Menu')],
        ],
    ]); ?>

</div>
