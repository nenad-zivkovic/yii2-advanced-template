<?php
namespace tests\codeception\frontend\functional;

use common\models\User;
use common\rbac\models\Role;
use common\models\Setting;
use tests\codeception\frontend\_pages\SignupPage;

class SignupCest
{
    private $rna; // registration needs activation

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
        // delete this signed up user
        User::deleteAll([
            'email' => 'demo@example.com',
            'username' => 'demo',
        ]);
        
        // delete roles
        Role::deleteAll();   

        $this->rna = Setting::findOne(['id' => Setting::REGISTRATION_NEEDS_ACTIVATION]);
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
     * Tests user normal signup process.
     * =========================================================================
     *
     * @param \codeception_frontend\FunctionalTester $I
     * 
     * @param \Codeception\Scenario $scenario
     * _________________________________________________________________________
     */
    public function testSignupWithoutActivation($I, $scenario)
    {
        // make sure we have adequate setting value before we start testing
        if ($this->rna->value === 1) 
        {
            $this->rna->value = 0; // registration needs activation is false
            $this->rna->save();
        }

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
            'password' => 'demo123',
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
            'password' => 'demo123',
        ]);

        $I->expectTo('see that user is logged in');
        $I->seeLink('Logout (demo)');     
    }

    /**
     * =========================================================================
     * Tests user signup with activation process.
     * =========================================================================
     *
     * @param \codeception_frontend\FunctionalTester $I
     * 
     * @param \Codeception\Scenario $scenario
     * _________________________________________________________________________
     */
    public function testSignupWithActivation($I, $scenario)
    {
        // make sure we have adequate setting value before we start testing
        if ($this->rna->value === 0) 
        {
            $this->rna->value = 1; // registration needs activation is true
            $this->rna->save();
        }

        $I->wantTo('ensure that signup with activation works');
        $signupPage = SignupPage::openBy($I);
        $I->see('Signup', 'h1');
        $I->see('Please fill out the following fields to signup:');

        //-- submit signup form with correct data --//
        $I->amGoingTo('submit signup form with correct data');
        $signupPage->submit([
            'username' => 'demo',
            'email' => 'demo@example.com',
            'password' => 'demo123',
        ]);

        $I->expectTo('see that user is not logged in');
        $I->seeInCurrentUrl('signup');
        $I->see('Hello demo.', '.alert-success');
        $I->dontSeeLink('Logout (demo)');
    }
}
