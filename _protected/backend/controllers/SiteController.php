<?php
namespace backend\controllers;

use common\models\Setting;
use common\models\LoginForm;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Yii;

/**
 * -----------------------------------------------------------------------------
 * Site controller.
 * It is responsible for displaying static pages, and logging users in and out.
 * -----------------------------------------------------------------------------
 */
class SiteController extends Controller
{
    /**
     * =========================================================================
     * Returns a list of behaviors that this component should behave as. 
     * =========================================================================
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * ======================================================================
     * Declares external actions for the controller.
     * ======================================================================
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * =========================================================================
     * Displays the index page.
     * =========================================================================
     *
     * @return mixed
     * _________________________________________________________________________
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * =========================================================================
     * Logs in the user if his account is activated.
     * =========================================================================
     *
     * @return mixed
     * _________________________________________________________________________
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) 
        {
            return $this->goHome();
        }

        // get setting value for "Login With Email"
        $lwe = Setting::get(Setting::LOGIN_WITH_EMAIL);

        // if "login with email" is true we instantiate LoginForm in "lwe" scenario
        $lwe ? $model = new LoginForm(['scenario' => 'lwe']) : $model = new LoginForm() ;

        // everything went fine, log in the user
        if ($model->load(Yii::$app->request->post()) && $model->login()) 
        {
            return $this->goBack();
        } 
        // errors will be displayed
        else 
        {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * =========================================================================
     * Logs out the user.
     * =========================================================================
     *
     * @return mixed  homepage view.
     * _________________________________________________________________________
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
