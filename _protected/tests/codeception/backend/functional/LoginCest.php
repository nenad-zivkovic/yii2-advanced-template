<?php
namespace tests\codeception\backend\functional;

use common\rbac\models\Role;
use tests\codeception\common\_pages\LoginPage;

class LoginCest
{
    /**
     * This method is called before each test method.
     *
     * @param \Codeception\Event\TestEvent $event
     */
    public function _before($event)
    {
    }

    /**
     * This method is called after each test method, even if test failed.
     *
     * @param \Codeception\Event\TestEvent $event
     */
    public function _after($event)
    {
    }

    /**
     * This method is called when test fails.
     *
     * @param \Codeception\Event\FailEvent $event
     */
    public function _fail($event)
    {
    }

    /**
     * Test login process.
     * Based on your system settings for 'Login With Email' it will 
     * run either testLoginWithEmail() or testLoginWithUsername method.
     * 
     * @param \Codeception\FunctionalTester $I
     * @param \Codeception\Scenario         $scenario
     */
    public function testLogin($I, $scenario)
    {
        // get setting value for 'Login With Email'
        $lwe = \Yii::$app->params['lwe'];

        $lwe ? $this->testLoginWithEmail($I) : $this->testLoginWithUsername($I);
    }

    /**
     * Test if active user can login with email/password combo.
     *
     * @param $I
     */
    private function testLoginWithEmail($I)
    {
        $I->wantTo('ensure that active user can login with email');
        $loginPage = LoginPage::openBy($I);

        //-- submit form with no data --//
        $I->amGoingTo('(login with email): submit login form with no data');
        $loginPage->login('', '');
        $I->expectTo('see validations errors');
        $I->see('Email cannot be blank.', '.help-block');
        $I->see('Password cannot be blank.', '.help-block');

        //-- submit form with wrong credentials --//
        $I->amGoingTo('(login with email): try to login with wrong credentials');
        $loginPage->login('wrong@example.com', 'wrong');
        if (method_exists($I, 'wait')) 
        {
            $I->wait(3); // only for selenium
        }
        $I->expectTo('see validations errors');
        $I->see('Incorrect email or password.', '.help-block');

        //-- login user with correct credentials --//
        $I->amGoingTo('try to log in correct user');
        $loginPage->login('admin@example.com', 'admin123');
        if (method_exists($I, 'wait')) 
        {
            $I->wait(3); // only for selenium
        }
        $I->expectTo('see that user is logged in');
        $I->seeLink('Logout (admin)');
        $I->dontSeeLink('Login');
        $I->dontSeeLink('Signup');
    }

    /**
     * Test if active user can login with username/password combo.
     *
     * @param $I
     */
    private function testLoginWithUsername($I)
    {
        $I->wantTo('ensure that active user can login with username');
        $loginPage = LoginPage::openBy($I);

        //-- submit form with no data --//
        $I->amGoingTo('(login with username): submit login form with no data');
        $loginPage->login('', '');
        $I->expectTo('see validations errors');
        $I->see('Username cannot be blank.', '.help-block');
        $I->see('Password cannot be blank.', '.help-block');

        //-- submit form with wrong credentials --//
        $I->amGoingTo('(login with username): try to login with wrong credentials');
        $loginPage->login('wrong', 'wrong');
        if (method_exists($I, 'wait')) 
        {
            $I->wait(3); // only for selenium
        }
        $I->expectTo('see validations errors');
        $I->see('Incorrect username or password.', '.help-block');

        //-- login user with correct credentials --//
        $I->amGoingTo('try to log in correct user');
        $loginPage->login('admin', 'admin123');
        if (method_exists($I, 'wait')) 
        {
            $I->wait(3); // only for selenium
        }
        $I->expectTo('see that user is logged in');
        $I->seeLink('Logout (admin)');
        $I->dontSeeLink('Login');
        $I->dontSeeLink('Signup');
    }

    /**
     * We want to be sure that not active user can not login.
     * If he try to login, he should get error flash message.
     * 
     * @param \Codeception\FunctionalTester $I
     * @param \Codeception\Scenario         $scenario
     */
    public function testLoginNotActiveUser($I, $scenario)
    {
        // get setting value for 'Login With Email'
        $lwe = \Yii::$app->params['lwe'];

        $field = ($lwe) ? 'tester@example.com' : 'tester';
        $error = ($lwe) ? 'email' : 'username';

        $I->wantTo("ensure that not active user can't login");
        $loginPage = LoginPage::openBy($I);

        //-- try to login user that has not activated his account yet --//
        $I->amGoingTo('try to log in not activated user');
        $loginPage->login($field, 'test123');
        $I->expectTo('see error message');
        $I->see('Incorrect '.$error.' or password.', '.help-block');
        $I->seeLink('Login');
    }
}
