<?php
namespace backend\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Yii;

/**
 * -----------------------------------------------------------------------------
 * BackendController extends Controller and implements the behaviors() method
 * where you can specify the access control ( AC filter + RBAC) for 
 * your controllers and their actions.
 * -----------------------------------------------------------------------------
 */
class BackendController extends Controller
{
    /**
     * =========================================================================
     * Returns a list of behaviors that this component should behave as. 
     * Here we use RBAC in combination with AccessControl filter.
     * =========================================================================
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'controllers' => ['setting'],
                        'actions' => ['index','create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('theCreator');
                        },
                    ],
                    [
                        'controllers' => ['user'],
                        'actions' => ['index', 'update', 'delete', 'update-role'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('admin');
                        },
                    ],
                    [
                        // other rules
                    ],

                ], // rules

            ], // access

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ], // verbs

        ]; // return

    } // behaviors

} // BackendController