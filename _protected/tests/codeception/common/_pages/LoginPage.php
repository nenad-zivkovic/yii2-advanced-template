<?php
namespace tests\codeception\common\_pages;

use yii\codeception\BasePage;
use common\models\Setting;

/**
 * Represents logging page
 * @property \codeception_frontend\AcceptanceTester|\codeception_frontend\FunctionalTester|
 * \codeception_backend\AcceptanceTester|\codeception_backend\FunctionalTester $actor
 */
class LoginPage extends BasePage
{
    public $route = 'site/login';

    /**
     * =========================================================================
     * Method representing user submitting login form.
     * =========================================================================
     *
     * @param string  $user      Can be users username or email.
     * 
     * @param string  $password
     * _________________________________________________________________________
     */
    public function login($user, $password)
    {
        // if we should login user with email use email field, otherwise use username
        $field = Setting::get(Setting::LOGIN_WITH_EMAIL) ? 'email' : 'username' ;

        $this->actor->fillField('input[name="LoginForm['.$field.']"]', $user);
        $this->actor->fillField('input[name="LoginForm[password]"]', $password);
        $this->actor->click('login-button');
    }
}
