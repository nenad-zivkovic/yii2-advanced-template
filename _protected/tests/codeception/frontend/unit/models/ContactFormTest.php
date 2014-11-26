<?php
namespace tests\codeception\frontend\unit\models;

use frontend\models\ContactForm;
use Codeception\Specify;
use tests\codeception\frontend\unit\TestCase;
use Yii;

class ContactFormTest extends TestCase
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
        unlink($this->getMessageFile());
        parent::tearDown();
    }

    /**
     * Test contact.
     */
    public function testContact()
    {
        $model = new ContactForm();

        $model->attributes = [
            'name' => 'Tester',
            'email' => 'tester@example.com',
            'subject' => 'very important letter subject',
            'body' => 'body of current message',
        ];

        $model->contact('admin@example.com');

        $this->specify('email should be send', function () {
            expect('email file should exist', file_exists($this->getMessageFile()))->true();
        });

        $this->specify('message should contain correct data', function () use ($model) {
            $emailMessage = file_get_contents($this->getMessageFile());

            expect('email should contain user name', $emailMessage)->contains($model->name);
            expect('email should contain sender email', $emailMessage)->contains($model->email);
            expect('email should contain subject', $emailMessage)->contains($model->subject);
            expect('email should contain body', $emailMessage)->contains($model->body);
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
}