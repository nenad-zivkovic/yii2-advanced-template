<?php
namespace tests\codeception\backend\acceptance;

use common\models\Setting;
use tests\codeception\common\_pages\LoginPage;

class LoginCest
{   
    private $lwe; // login with email

    /**
     * =========================================================================
     * This method is called before each test method.
     * =========================================================================
     *
     * @param \Codeception\Event\TestEvent $event
     * _________________________________________________________________________
     */
    public function _before($event)
    {
        $this->lwe = Setting::findOne(['id' => Setting::LOGIN_WITH_EMAIL]);
    }

    /**
     * =========================================================================
     * This method is called after each test method, even if test failed.
     * =========================================================================
     *
     * @param \Codeception\Event\TestEvent $event
     * _________________________________________________________________________
     */
    public function _after($event)
    {
    }

    /**
     * =========================================================================
     * This method is called when test fails.
     * =========================================================================
     *
     * @param \Codeception\Event\FailEvent $event
     * _________________________________________________________________________
     */
    public function _fail($event)
    {
    }

    /**
     * =========================================================================
     * Test if active user can login with email/password combo.
     * =========================================================================
     *
     * @param \codeception_backend\AcceptanceTester $I
     * 
     * @param \Codeception\Scenario $scenario
     * _________________________________________________________________________
     */
    public function testLoginWithEmail($I, $scenario)
    {
        // make sure we have adequate setting value before we start testing
        if ($this->lwe->value === 0) 
        {
            $this->lwe->value = 1; // login with email is true
            $this->lwe->save();
        }

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
        $I->expectTo('see validations errors');
        $I->see('Incorrect email or password.', '.help-block');

        //-- login user with correct credentials --//
        $I->amGoingTo('try to log in correct user');
        $loginPage->login('member@example.com', 'member123');
        $I->expectTo('see that user is logged in');
        $I->seeLink('Logout (member)');
        $I->dontSeeLink('Login');
        $I->dontSeeLink('Signup');
    }

    /**
     * =========================================================================
     * Test if active user can login with username/password combo.
     * =========================================================================
     *
     * @param \codeception_backend\AcceptanceTester $I
     * 
     * @param \Codeception\Scenario $scenario
     * _________________________________________________________________________
     */
    public function testLoginWithUsername($I, $scenario)
    {
        // make sure we have adequate setting value before we start testing
        if ($this->lwe->value === 1) 
        {
            $this->lwe->value = 0; // login with email is false
            $this->lwe->save();
        }

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
        $I->expectTo('see validations errors');
        $I->see('Incorrect username or password.', '.help-block');

        //-- login user with correct credentials --//
        $I->amGoingTo('try to log in correct user');
        $loginPage->login('member', 'member123');
        $I->expectTo('see that user is logged in');
        $I->seeLink('Logout (member)');
        $I->dontSeeLink('Login');
        $I->dontSeeLink('Signup');
    }

    /**
     * =========================================================================
     * We want to be sure that not active user can not login.
     * NOTE: we are testing username/password combo, there is no need to test 
     * email/password combo too.
     * =========================================================================
     * 
     * @param \codeception_backend\AcceptanceTester $I
     * 
     * @param \Codeception\Scenario $scenario
     * _________________________________________________________________________
     */
    public function testLoginNotActiveUser($I, $scenario)
    {
        // make sure we have adequate setting value before we start testing
        if ($this->lwe->value === 1) 
        {
            $this->lwe->value = 0; // login with email is false
            $this->lwe->save();
        }

        $I->wantTo("ensure that not active user can't login");
        $loginPage = LoginPage::openBy($I);

        //-- try to login user that has not activated his account yet --//
        $I->amGoingTo('try to log in not activated user');
        $loginPage->login('tester', 'test123');
        $I->expectTo('see error message');
        $I->see('Incorrect username or password.', '.help-block');
        $I->seeLink('Login');
    }
}
