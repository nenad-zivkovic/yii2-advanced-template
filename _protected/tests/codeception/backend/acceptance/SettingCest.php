<?php
namespace tests\codeception\backend\acceptance;

use common\rbac\models\Role;
use common\models\Setting;
use tests\codeception\backend\_pages\SettingsPage;
use tests\codeception\common\_pages\LoginPage;
use Yii;

class SettingCest
{   
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
        // delete leftover roles
        Role::deleteAll();
    
        //-- make sure our testing user has theCreator role --//
        $auth = Yii::$app->authManager;
        $role = $auth->getRole('theCreator');
        $auth->assign($role, 1);
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
        // delete created role
        Role::deleteAll();
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
     * Test if admin can use setting page.
     *
     * @param \codeception_backend\AcceptanceTester $I
     * 
     * @param \Codeception\Scenario $scenario
     * _________________________________________________________________________
     */
    public function testSettingPageWorks($I, $scenario)
    {
        $loginPage = LoginPage::openBy($I);

        $field = Setting::get(Setting::LOGIN_WITH_EMAIL) ? 'creator@example.com' : 'The Creator';

        $I->wantTo('ensure that settings page works');

        $I->amGoingTo('try to log in The Creator so he can use settings');
        $loginPage->login($field, 'creator123');

        $I->expectTo('see that The Creator is logged');
        $I->seeLink('Logout (The Creator)');
        $I->SeeLink('Settings');
        $I->dontSeeLink('Login');

        $I->amGoingTo('try to open settings page');
        SettingsPage::openBy($I);

        $I->see('Settings', 'h1');
    }
}
