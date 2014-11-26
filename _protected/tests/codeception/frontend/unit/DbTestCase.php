<?php
namespace tests\codeception\frontend\unit;

/**
 * Class DbTestCase
 * 
 * @package tests\codeception\frontend\unit
 */
class DbTestCase extends \yii\codeception\DbTestCase
{
    public $appConfig = '@tests/codeception/config/frontend/unit.php';
}
