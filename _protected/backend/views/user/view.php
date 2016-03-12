<?php
use common\helpers\CssHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = Html::encode($model->username);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1>
        <?= $this->title ?>
        <div class="pull-right">
            <?= Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']) ?>
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], ['class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this user?'),
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            //'password_hash',
            [
                'attribute'=>'status',
                'value' => '<span class="'.CssHelper::userStatusCss($model->status).'">
                                '.$model->getStatusName($model->status).'
                            </span>',
                'format' => 'raw'
            ],
            [
                'attribute'=>'item_name',
                'value' => '<span class="'.CssHelper::roleCss($model->getRoleName()).'">
                                '.$model->getRoleName().'
                            </span>',
                'format' => 'raw'
            ],
            //'auth_key',
            //'password_reset_token',
            //'account_activation_token',
            'created_at:date',
            'updated_at:date',
        ],
    ]) ?>

</div>
