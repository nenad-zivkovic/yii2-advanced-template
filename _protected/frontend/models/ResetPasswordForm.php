<?php
namespace frontend\models;

use common\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;

/**
 * -----------------------------------------------------------------------------
 * Password reset form.
 * -----------------------------------------------------------------------------
 */
class ResetPasswordForm extends Model
{
    public $password;

    /**
     * @var \common\models\User
     */
    private $_user;

    /**
     * =========================================================================
     * Creates a form model given a token.
     * =========================================================================
     *
     * @param  string  $token                   Password reset token.
     *
     * @param  array   $config                  Name-value pairs that will be 
     *                                          used to initialize the object
     *                                          properties.
     *
     * @throws \yii\base\InvalidParamException  If token is empty or not valid.
     * _________________________________________________________________________
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) 
        {
            throw new InvalidParamException('Password reset token cannot be blank.');
        }

        $this->_user = User::findByPasswordResetToken($token);

        if (!$this->_user) 
        {
            throw new InvalidParamException('Wrong password reset token.');
        }

        parent::__construct($config);
    }

    /**
     * =========================================================================
     * Returns the validation rules for attributes.
     * =========================================================================
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }
   
    /**
     * =========================================================================
     * Resets password.
     * =========================================================================
     *
     * @return boolean  Whether the password was reset.
     * _________________________________________________________________________
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->password = $this->password;
        $user->removePasswordResetToken();

        return $user->save();
    }
}
