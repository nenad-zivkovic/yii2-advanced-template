<?php
namespace tests\codeception\frontend\unit\models;

use frontend\models\ResetPasswordForm;
use tests\codeception\frontend\unit\DbTestCase;
use tests\codeception\common\fixtures\UserFixture;

class ResetPasswordFormTest extends DbTestCase
{
    /**
     * Resetting password if token is wrong should not be possible.
     *
     * @expectedException \yii\base\InvalidParamException
     */
    public function testResetWrongToken()
    {
        new ResetPasswordForm('notexistingtoken_1391882543');
    }

    /**
     * Resetting password if token is empty should not be possible.
     *
     * @expectedException \yii\base\InvalidParamException
     */
    public function testResetEmptyToken()
    {
        new ResetPasswordForm('');
    }

    /**
     * Make sure that we can reset password if token is correct.
     */
    public function testResetCorrectToken()
    {
        $form = new ResetPasswordForm($this->user[0]['password_reset_token']);
        
        expect('password should be reseted', $form->resetPassword())->true();
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
