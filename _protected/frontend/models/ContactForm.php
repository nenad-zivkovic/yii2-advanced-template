<?php
namespace frontend\models;

use yii\base\Model;
use Yii;

/**
 * -----------------------------------------------------------------------------
 * ContactForm is the model behind the contact form.
 * -----------------------------------------------------------------------------
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;

    /**
     * =========================================================================
     * Returns the validation rules for attributes.
     * =========================================================================
     */
    public function rules()
    {
        return [
            [['name', 'email', 'subject', 'body'], 'required'],
            ['email', 'email'],
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * =========================================================================
     * Returns the attribute labels. 
     * =========================================================================
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    /**
     * =========================================================================
     * Sends an email to the specified email address using the information 
     * collected by this model.
     * =========================================================================
     *
     * @param  string  $email  The target email address.
     *
     * @return boolean         Whether the email was sent.
     * _________________________________________________________________________
     */
    public function sendEmail($email)
    {
        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([$this->email => $this->name])
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();
    }
}
