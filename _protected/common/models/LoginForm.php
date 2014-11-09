<?php
namespace common\models;

use yii\base\Model;
use Yii;

/**
 * -----------------------------------------------------------------------------
 * LoginForm is the model behind the login form.
 * -----------------------------------------------------------------------------
 */
class LoginForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $rememberMe = true;

    /**
     * @var \common\models\User
     */
    private $_user = false;

    /**
     * =========================================================================
     * Returns the validation rules for attributes.
     * =========================================================================
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
     * =========================================================================
     * Validates the password.
     * This method serves as the inline validation for password.
     * =========================================================================
     *
     * @param string  $attribute  The attribute currently being validated.
     *
     * @param array   $params     The additional name-value pairs.
     * _________________________________________________________________________
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) 
        {
            $user = $this->user;

            if (!$user || !$user->validatePassword($this->password)) 
            {
                // if scenario is 'lwe' we use email, otherwise we use username
                $field = ($this->scenario === 'lwe') ? 'email' : 'username' ;

                $this->addError($attribute, 'Incorrect '.$field.' or password.');
            }
        }
    }

    /**
     * =========================================================================
     * Logs in a user using the provided username|email and password.
     * =========================================================================
     *
     * @return boolean  Whether the user is logged in successfully.
     * _________________________________________________________________________
     */
    public function login()
    {
        if ($this->validate()) 
        {
            return Yii::$app->user->login($this->user, $this->rememberMe ? 3600 * 24 * 30 : 0);
        } 
        else 
        {
            return false;
        }
    }

    /**
     * =========================================================================
     * Finds user by username or email in 'lwe' scenario. 
     * Since this is a getter method, we are using it inside our class 
     * like a property: $this->user.
     * =========================================================================
     * 
     * @return User|null
     * _________________________________________________________________________
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
    
    /**
     * =========================================================================
     * Checks to see if the given user has NOT activated his account yet.
     * We first check if user exists in our system, 
     * and then did he activated his account.
     * =========================================================================
     *
     * @return boolean  True if not activated.
     * _________________________________________________________________________
     */
    public function notActivated()
    {
        // if scenario is 'lwe' we will use email as our username, otherwise we use username
        $username = ($this->scenario === 'lwe') ? $this->email : $this->username;

        if ($user = User::userExists($username, $this->password, $this->scenario))
        {
            if ($user->status === User::STATUS_NOT_ACTIVE)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
}
