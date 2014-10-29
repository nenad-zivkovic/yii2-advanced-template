<?php

use backend\helpers\CssHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;

/* @var $settings common\models\Setting */
/* @var $this yii\web\View */

$this->title = 'Settings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settings-index">

    <h1>
        <?= Html::encode($this->title) ?>

        <span class="pull-right">
            <?= Html::a('Create Setting', ['create'], ['class' => 'btn btn-success']) ?>
        </span>

    </h1>

    <table class="table table-striped table-bordered">

        <thead>
        <tr>
            <th> <?= $sort->link('id') ?> </th>
            <th> <?= $sort->link('name') ?> </th>
            <th> <?= $sort->link('value') ?> </th>
            <th> &nbsp; </th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($settings as $setting): ?>
        <?php $value = ($setting->value == 1) ? 'Yes' : 'No' ?>
        <tr>   
            <td> <?= $setting->id ?> </td>
            <td> <?= $setting->name ?> </td>
            <td class="<?= CssHelper::settingValueCss($value) ?>" > <?= $value ?> </td>
            <td>
                <?= Html::a('Update', 
                    ['update', 'id' => $setting->id], 
                    ['class' => 'btn btn-primary btn-xs']) 
                ?>

                <?= Html::a('Delete', 
                    ['delete', 'id' => $setting->id], 
                    ['class' => 'btn btn-danger btn-xs',
                     'data'  => ['confirm' => 'Are you sure you want to delete this item?',
                                 'method' => 'post']])
                ?>
            </td>
        </tr>
        <?php endforeach ?>
        </tbody>

    </table>

    <?= LinkPager::widget([
            'pagination' => $pagination,
        ]);
    ?>

</div>
