<?php
namespace tests\codeception\common\unit\models;

use common\models\LoginForm;
use Codeception\Specify;
use tests\codeception\common\unit\DbTestCase;
use tests\codeception\common\fixtures\UserFixture;
use Yii;

/**
 * Login form test
 */
class LoginFormTest extends DbTestCase
{
    use Specify;

    /**
     * Create the objects against which you will test.  
     */
    public function setUp()
    {
        parent::setUp();

        Yii::configure(Yii::$app, [
            'components' => [
                'user' => [
                    'class' => 'yii\web\User',
                    'identityClass' => 'common\models\UserIdentity',
                ],
            ],
        ]);
    }

    /**
     * Clean up the objects against which you tested. 
     */
    protected function tearDown()
    {
        Yii::$app->user->logout();
        parent::tearDown();
    }

   /**
     * If username is wrong user should not be able to log in.
     */
    public function testLoginWrongUsername()
    {
        $model = new LoginForm();
        $model->username = 'wrong';
        $model->password = 'member123';

        $this->specify('user should not be able to login, when username is wrong', function () use ($model) {
            expect('model should not login user', $model->login())->false();
            expect('user should not be logged in', Yii::$app->user->isGuest)->true();
        });
    }

    /**
     * If email is wrong user should not be able to log in.
     */
    public function testLoginWrongEmail()
    {
        $model = new LoginForm(['scenario' => 'lwe']);
        $model->email = 'member@wrong.com';
        $model->password = 'member123';

        $this->specify('user should not be able to login, when email is wrong', function () use ($model) {
            expect('model should not login user', $model->login())->false();
            expect('user should not be logged in', Yii::$app->user->isGuest)->true();
        }); 
    }

    /**
     * If password is wrong user should not be able to log in.
     */
    public function testLoginWrongPassword()
    {
        $model = new LoginForm();
        $model->username = 'member';
        $model->password = 'test';

        $this->specify('user should not be able to login with wrong password', function () use ($model) {
            expect('model should not login user', $model->login())->false();
            expect('user should not be logged in', Yii::$app->user->isGuest)->true();
        });
    }

    /**
     * If user has not activated his account he should not be able to log in.
     */
    public function testLoginInactiveUser()
    {
        $model = new LoginForm();
        $model->username = 'tester';
        $model->password = 'test123';

        $this->specify('not activated user should not be able to login', function () use ($model) {
            expect('model should not login user', $model->login())->false();    
            expect('user should not be logged in', Yii::$app->user->isGuest)->true();
        });
    } 

    /**
     * Active user should be able to log in if he enter correct credentials.
     */
    public function testLoginActiveUser()
    {
        $model = new LoginForm();
        $model->username = 'member';
        $model->password = 'member123';
        
        $this->specify('user should be able to login with correct credentials', function () use ($model) {
            expect('model should login user', $model->login())->true();
            expect('user should be logged in', Yii::$app->user->isGuest)->false();
        });
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
                'dataFile' => '@tests/codeception/common/unit/fixtures/data/models/user.php'
            ],
        ];
    }
}
