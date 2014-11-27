<?php
namespace tests\codeception\common\_pages;

use yii\codeception\BasePage;

/**
 * Represents Login Page
 */
class LoginPage extends BasePage
{
    public $route = 'site/login';

    /**
     * Method representing user submitting login form.
     *
     * @param $user
     * @param $password
     */
    public function login($user, $password)
    {
        // if 'Login With Email' is true use email field, otherwise use username
        $field = (\Yii::$app->params['lwe']) ? 'email' : 'username' ;

        $this->actor->fillField('input[name="LoginForm['.$field.']"]', $user);
        $this->actor->fillField('input[name="LoginForm[password]"]', $password);
        $this->actor->click('login-button');
    }
}
