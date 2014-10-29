<?php

use backend\helpers\CssHelper;
use common\models\User;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <table class="table table-striped table-bordered">

        <thead>
        <tr>
            <th> <?= $sort->link('id') ?> </th>
            <th> <?= $sort->link('username') ?> </th>
            <th> <?= $sort->link('email') ?> </th>
            <th> <?= $sort->link('status') ?> </th>
            <th> <?= $sort->link('item_name') ?> </th>
            <th> &nbsp; </th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($users as $user): ?>

        <?php $status = $user->statusName ?>
        <?php $roleName = $user->role->item_name ?>
        <?php $role = ($roleName === 'theCreator') ? 'The Creator' : $roleName ?>

        <tr>   
            <td> <?= $user->id ?> </td>
            <td> <?= Html::encode($user->username) ?> </td>
            <td> <a href="mailto:<?= $user->email ?>" > <?= $user->email ?> </a></td>
            <td class="<?= CssHelper::statusCss($status) ?>" > <?= $status ?> </td>
            <td class="<?= CssHelper::roleCss($role) ?>"> <?= Html::encode($role) ?> </td>
            <td>
                <?= Html::a('Update role', 
                    ['update-role', 'id' => $user->id], 
                    ['class' => 'btn btn-primary btn-xs']) 
                ?>

                <?= Html::a('Delete user', 
                    ['delete', 'id' => $user->id], 
                    ['class' => 'btn btn-danger btn-xs',
                     'data' => ['confirm' => 'Are you sure you want to delete this item?',
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