<?php
namespace tests\codeception\frontend\unit\models;

use frontend\models\AccountActivation;
use tests\codeception\frontend\unit\DbTestCase;
use tests\codeception\common\fixtures\UserFixture;

class AccountActivationTest extends DbTestCase
{
    /**
     * If token is wrong account activation should not be possible.
     *
     * @expectedException \yii\base\InvalidParamException
     */
    public function testActivationWrong()
    {
        new AccountActivation('notexistingtoken_1391882543');
    }

    /**
     * If token is empty account activation should not be possible.
     *
     * @expectedException \yii\base\InvalidParamException
     */
    public function testActivationEmpty()
    {
        new AccountActivation('');
    }

    /**
     * Make sure that user can activate his account if token is correct.
     */
    public function testActivationCorrect()
    {
        $model = new AccountActivation($this->user[1]['account_activation_token']);
        
        expect('account should be activated', $model->activateAccount())->true();
    }

    /**
     * Declares the fixtures that are needed by the current test case.
     *
     * @return array
     */
    public function fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => '@tests/codeception/frontend/unit/fixtures/data/models/user.php'
            ],
        ];
    }
}
