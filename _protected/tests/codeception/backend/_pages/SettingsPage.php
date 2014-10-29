<?php
namespace tests\codeception\backend\_pages;

use yii\codeception\BasePage;

/**
 * Represents settings page
 * @property \codeception_backend\AcceptanceTester|\codeception_backend\FunctionalTester $actor
 */
class SettingsPage extends BasePage
{
    public $route = 'setting/index';
}
