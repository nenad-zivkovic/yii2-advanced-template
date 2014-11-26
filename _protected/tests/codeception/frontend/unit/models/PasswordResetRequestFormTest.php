<?php
namespace tests\codeception\frontend\models;

use frontend\models\PasswordResetRequestForm;
use common\models\User;
use Codeception\Specify;
use tests\codeception\frontend\unit\DbTestCase;
use tests\codeception\common\fixtures\UserFixture;
use Yii;

class PasswordResetRequestFormTest extends DbTestCase
{
    use Specify;

    /**
     * Create the objects against which you will test.
     */
    protected function setUp()
    {
        parent::setUp();

        Yii::$app->mailer->fileTransportCallback = function ($mailer, $message) {
            return 'testing_message.eml';
        };
    }

    /**
     * Clean up the objects against which you tested.
     */
    protected function tearDown()
    {
        @unlink($this->getMessageFile());

        parent::tearDown();
    }

    /**
     * Make sure that sending email to wrong user will fail.
     */
    public function testSendEmailWrongUser()
    {
        $this->specify('no user with such email, message should not be sent', function () {

            $model = new PasswordResetRequestForm();
            $model->email = 'not-existing-email@example.com';

            expect('email not send', $model->sendEmail())->false();

        });

        $this->specify('user is not active, message should not be sent', function () {

            $model = new PasswordResetRequestForm();
            $model->email = $this->user[1]['email'];

            expect('email not send', $model->sendEmail())->false();

        });
    }

    /**
     * Make sure that sending email to correct user is working.
     */
    public function testSendEmailCorrectUser()
    {
        $model = new PasswordResetRequestForm();
        $model->email = $this->user[0]['email'];
        $user = User::findOne(['password_reset_token' => $this->user[0]['password_reset_token']]);

        expect('email sent', $model->sendEmail())->true();
        expect('user has valid token', $user->password_reset_token)->notNull();

        $this->specify('message has correct format', function () use ($model) {

            expect('message file exists', file_exists($this->getMessageFile()))->true();

            $message = file_get_contents($this->getMessageFile());
            expect('message "from" is correct', $message)->contains(Yii::$app->params['supportEmail']);
            expect('message "to" is correct', $message)->contains($model->email);

        });
    }

    /**
     * Get message file that our test will create to put contact data in
     * (we are simulating email sending in our test by writing data to file).
     *
     * @return string
     */
    private function getMessageFile()
    {
        return Yii::getAlias(Yii::$app->mailer->fileTransportPath) . '/testing_message.eml';
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
