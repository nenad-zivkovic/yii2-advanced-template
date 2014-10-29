<?php
namespace frontend\models;

use common\models\User;
use yii\base\InvalidParamException;
use yii\base\Object;
use Yii;

/**
 * -----------------------------------------------------------------------------
 * Class representing account activation.
 * -----------------------------------------------------------------------------
 */
class AccountActivation extends Object
{
    /**
     * @var \common\models\User
     */
    private $_user;

    /**
     * =========================================================================
     * Creates the user object given a token.
     * =========================================================================
     *
     * @param  string  $token                   Account activation token.
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
            throw new InvalidParamException('Account activation token cannot be blank.');
        }

        $this->_user = User::findByAccountActivationToken($token);

        if (!$this->_user) 
        {
            throw new InvalidParamException('Wrong account activation token. Please try again.');
        }

        parent::__construct($config);
    }
   
    /**
     * =========================================================================
     * Activates account.
     * =========================================================================
     *
     * @return boolean  Whether the account was activated.
     * _________________________________________________________________________
     */
    public function activateAccount()
    {
        $user = $this->_user;

        $user->status = User::STATUS_ACTIVE;
        $user->removeAccountActivationToken();

        return $user->save();
    }

    /**
     * =========================================================================
     * Returns the username of the user who has activated account. 
     * =========================================================================
     *
     * @return string  Username.
     * _________________________________________________________________________
     */
    public function getUsername()
    {
        $user = $this->_user;
        return $user->username;
    }
}
