<?php
namespace tests\codeception\frontend\functional;

use common\models\User;
use common\rbac\models\Role;
use tests\codeception\frontend\_pages\SignupPage;

class SignupCest
{
    /**
     * This method is called before each test method.
     *
     * @param \Codeception\Event\TestEvent $event
     */
    public function _before($event)
    {
        // delete this signed up user
        User::deleteAll([
            'email' => 'demo@example.com',
            'username' => 'demo',
        ]);
        
        // delete roles
        Role::deleteAll();   
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
     * Test user signup process.
     * Based on your system settings for 'Registration Needs Activation' it will 
     * run either testSignupWithActivation() or testSignupWithoutActivation() method.
     * 
     * @param \Codeception\FunctionalTester $I
     * @param \Codeception\Scenario         $scenario
     */
    public function testSignup($I, $scenario)
    {
        // get setting value for 'Registration Needs Activation'
        $rna = \Yii::$app->params['rna'];

        $rna ? $this->testSignupWithActivation($I) : $this->testSignupWithoutActivation($I);
    }

    /**
     * Tests user normal signup process.
     *
     * @param $I
     */
    private function testSignupWithoutActivation($I)
    {
        $I->wantTo('ensure that normal signup works');
        $signupPage = SignupPage::openBy($I);
        $I->see('Signup', 'h1');
        $I->see('Please fill out the following fields to signup:');

        //-- submit form with no data --//
        $I->amGoingTo('submit signup form with no data');
        $signupPage->submit([]);

        $I->expectTo('see validation errors');
        $I->see('Username cannot be blank.', '.help-block');
        $I->see('Email cannot be blank.', '.help-block');
        $I->see('Password cannot be blank.', '.help-block');

        //-- submit signup form with not correct email --//
        $I->amGoingTo('submit signup form with not correct email');
        $signupPage->submit([
            'username' => 'demo',
            'email' => 'demo',
            'password' => 'asDF@#12asdf',
        ]);

        $I->expectTo('see that email address is wrong');
        $I->dontSee('Username cannot be blank.', '.help-block');
        $I->dontSee('Password cannot be blank.', '.help-block');
        $I->see('Email is not a valid email address.', '.help-block');

        //-- submit signup form with correct email --//
        $I->amGoingTo('submit signup form with correct email');
        $signupPage->submit([
            'username' => 'demo',
            'email' => 'demo@example.com',
            'password' => 'asDF@#12asdf',
        ]);

        $I->expectTo('see that user is logged in');
        $I->seeLink('Logout (demo)');     
    }

    /**
     * Tests user signup with activation process.
     *
     * @param $I
     */
    private function testSignupWithActivation($I)
    {
        $I->wantTo('ensure that signup with activation works');
        $signupPage = SignupPage::openBy($I);
        $I->see('Signup', 'h1');
        $I->see('Please fill out the following fields to signup:');

        //-- submit signup form with correct data --//
        $I->amGoingTo('submit signup form with correct data');
        $signupPage->submit([
            'username' => 'demo',
            'email' => 'demo@example.com',
            'password' => 'asDF@#12asdf',
        ]);

        $I->expectTo('see that user is not logged in');
        $I->seeInCurrentUrl('signup');
        $I->see('Hello demo.', '.alert-success');
        $I->dontSeeLink('Logout (demo)');
    }    
}
