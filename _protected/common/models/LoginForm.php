<?php
namespace common\models;

use yii\base\Model;
use Yii;

/**
 * LoginForm is the model behind the login form.
 *
 * @package common\models
 */
class LoginForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $rememberMe = true;
    public $status; // holds the information about user status

    /**
     * @var \common\models\User
     */
    private $_user = false;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['email', 'email'],
            ['password', 'validatePassword'],
            ['rememberMe', 'boolean'],
            // username and password are required on default scenario
            [['username', 'password'], 'required', 'on' => 'default'],
            // email and password are required on 'lwe' (login with email) scenario
            [['email', 'password'], 'required', 'on' => 'lwe'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute The attribute currently being validated.
     * @param array  $params    The additional name-value pairs.
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) 
        {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) 
            {
                // if scenario is 'lwe' we use email, otherwise we use username
                $field = ($this->scenario === 'lwe') ? 'email' : 'username' ;

                $this->addError($attribute, 'Incorrect '.$field.' or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username|email and password.
     *
     * @return bool Whether the user is logged in successfully.
     */
    public function login()
    {
        if ($this->validate())
        {
            // get user status if he exists, otherwise assign not active as default
            $this->status = ($user = $this->getUser()) ? $user->status : User::STATUS_NOT_ACTIVE;

            // if we have active and valid user log him in
            if ($this->status === User::STATUS_ACTIVE) 
            {
                return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
            } 
            else 
            {
                return false; // user is not active
            }
        } 
        else 
        {
            return false;
        }
    }

    /**
     * Finds user by username or email in 'lwe' scenario.
     *
     * @return User|null|static
     */
    public function getUser()
    {
        if ($this->_user === false) 
        {
            // in 'lwe' scenario we find user by email, otherwise by username
            if ($this->scenario === 'lwe')
            {
                $this->_user = User::findByEmail($this->email);
            } 
            else 
            {
                $this->_user = User::findByUsername($this->username);
            } 
        }

        return $this->_user;
    }
}
