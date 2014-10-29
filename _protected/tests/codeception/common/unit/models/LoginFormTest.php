<?php
namespace tests\codeception\common\unit\models;

use common\models\Setting;
use common\models\LoginForm;
use tests\codeception\common\unit\DbTestCase;
use tests\codeception\common\fixtures\UserFixture;
use Codeception\Specify;
use Yii;

class LoginFormTest extends DbTestCase
{
    use Specify;

    private $lwe; // login with email

    /**
     * =========================================================================
     * Create the objects against which you will test.  
     * =========================================================================
     */
    public function setUp()
    {
        parent::setUp();

        Yii::configure(Yii::$app, [
            'components' => [
                'user' => [
                    'class' => 'yii\web\User',
                    'identityClass' => 'common\models\User',
                ],
            ],
        ]);

        $this->lwe = Setting::findOne(['id' => Setting::LOGIN_WITH_EMAIL]);
    }

    /**
     * =========================================================================
     * Clean up the objects against which you tested. 
     * =========================================================================
     */
    protected function tearDown()
    {
        Yii::$app->user->logout();
        parent::tearDown();
    }

    /**
     * =========================================================================
     * If username is wrong user should not be able to log in. 
     * =========================================================================
     */
    public function testLoginWrongUsername()
    {
        // make sure we have adequate setting value before we start testing
        if ($this->lwe->value === 1) 
        {
            $this->lwe->value = 0; // login with email is false
            $this->lwe->save();
        }

        $model = new LoginForm([
            'username' => 'wrong',
            'password' => 'member123',
        ]);

        $this->specify('user should not be able to login, when username is wrong', 
            function () use ($model) {
            expect('model should not login user', $model->login())->false();
            expect('user should not be logged in', Yii::$app->user->isGuest)->true();
        });
    }

    /**
     * =========================================================================
     * If email is wrong user should not be able to log in.  
     * =========================================================================
     */
    public function testLoginWrongEmail()
    {
        // make sure we have adequate setting value before we start testing
        if ($this->lwe->value === 0) 
        {
            $this->lwe->value = 1; // login with email is true
            $this->lwe->save();
        }

        $model = new LoginForm(['scenario' => 'lwe']);
        $model->email = 'member@wrong.com';
        $model->password = 'member123';

        $this->specify('user should not be able to login, when email is wrong', 
            function () use ($model) {
            expect('model should not login user', $model->login())->false();
            expect('user should not be logged in', Yii::$app->user->isGuest)->true();
        });
    }

    /**
     * =========================================================================
     * If password is wrong user should not be able to log in. 
     * NOTE: it is enough to test only email/password combo, we do not need to 
     * test username/password too. 
     * =========================================================================
     */
    public function testLoginWrongPassword()
    {
        // make sure we have adequate setting value before we start testing
        if ($this->lwe->value === 0) 
        {
            $this->lwe->value = 1; // login with email is true
            $this->lwe->save();
        }

        $model = new LoginForm(['scenario' => 'lwe']);
        $model->email = 'member@example.com';
        $model->password = 'password';
        
        $this->specify('user should not be able to login with wrong password', 
            function () use ($model) {
            expect('model should not login user', $model->login())->false();
            expect('error message should be set', $model->errors)->hasKey('password');
            expect('user should not be logged in', Yii::$app->user->isGuest)->true();
        });
    }

    /**
     * =========================================================================
     * If user has not activated his account he should not be able to log in.
     * NOTE: it is enough to test only email/password combo, we do not need to 
     * test username/password too. 
     * =========================================================================
     */
    public function testLoginNotActivatedUser()
    {
        // make sure we have adequate setting value before we start testing
        if ($this->lwe->value === 0) 
        {
            $this->lwe->value = 1; // login with email is true
            $this->lwe->save();
        }

        $model = new LoginForm(['scenario' => 'lwe']);
        $model->email = 'tester@example.com';
        $model->password = 'test123';

        $this->specify('not activated user should not be able to login', function () use ($model) {
            expect('model should not login user', $model->login())->false();    
            expect('user should not be logged in', Yii::$app->user->isGuest)->true();
        });
    } 

    /**
     * =========================================================================
     * Active user should be able to log in if he enter correct credentials.
     * NOTE: it is enough to test only email/password combo, we do not need to 
     * test username/password too. 
     * =========================================================================
     */
    public function testLoginActivatedUser()
    {
        // make sure we have adequate setting value before we start testing
        if ($this->lwe->value === 0) 
        {
            $this->lwe->value = 1; // login with email is true
            $this->lwe->save();
        }

        $model = new LoginForm(['scenario' => 'lwe']);
        $model->email = 'member@example.com';
        $model->password = 'member123';
        
        $this->specify('user should be able to login with correct credentials', 
            function () use ($model) {
            expect('model should login user', $model->login())->true();
            expect('error message should not be set', $model->errors)->hasntKey('password');
            expect('user should be logged in', Yii::$app->user->isGuest)->false();
        });
    } 

    /**
     * =========================================================================
     * Declares the fixtures that are needed by the current test case. 
     * =========================================================================
     */
    public function fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => '@tests/codeception/common/unit/fixtures/data/models/user.php'
            ],
        ];
    }

}
