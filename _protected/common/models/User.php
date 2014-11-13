<?php
namespace common\models;

use common\rbac\models\Role;
use nenad\passwordStrength\StrengthValidator;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use Yii;

/**
 * ------------------------------------------------------------------------------
 * User model.
 *
 * @property integer $id
 * @property string  $username
 * @property string  $password_hash
 * @property string  $password_reset_token
 * @property string  $email
 * @property string  $account_activation_token
 * @property string  $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Role $role
 * -----------------------------------------------------------------------------
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 10;

    public $newPassword; // used when updating account

    /**
     * =========================================================================
     * Declares the name of the database table associated with this AR class. 
     * =========================================================================
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

  /**
     * =========================================================================
     * Returns the validation rules for attributes.
     * NOTE: We are using these rules when updating admin|The Creator account.
     * =========================================================================
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'filter', 'filter' => 'trim'],
            [['username', 'email'], 'required'],
            ['email', 'email'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            
            // use StrengthValidator
            // presets are located in: vendor/nenad/yii2-password-strength/presets.php
            [['newPassword'], StrengthValidator::className(), 'preset'=>'normal'],
            
            ['username', 'unique', 'message' => 'This username has already been taken.'],
            ['email', 'unique', 'message' => 'This email address has already been taken.'],
        ];
    }

    /**
     * =========================================================================
     * Returns a list of behaviors that this component should behave as. 
     * =========================================================================
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * =========================================================================
     * Relation with Role class. 
     * =========================================================================
     */
    public function getRole()
    {
        // User has_one Role via Role.user_id -> id
        return $this->hasOne(Role::className(), ['user_id' => 'id']);
    }

//------------------------------------------------------------------------------------------------//
// IDENTITY FINDERS
//------------------------------------------------------------------------------------------------//

    /**
     * =========================================================================
     * Finds an identity by the given ID.
     * =========================================================================
     *
     * @param  integer  $id  The user id.
     *
     * @return static|null
     * _________________________________________________________________________
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * =========================================================================
     * Finds an identity by the given token. TODO!
     * =========================================================================
     *
     * @param  mixed $token The token to be looked for.
     *
     * @param  mixed $type The type of the token. The value of this
     *                        parameter depends on the implementation.
     *                        For example, yii\filters\auth\HttpBearerAuth
     *                        will set this parameter to be
     *                        Yii\filters\auth\HttpBearerAuth.
     *
     * @throws NotSupportedException
     *
     * @return object|null    The identity object that matches the given token.
     *                        Null should be returned if such an identity
     *                        cannot be found or the identity is not in an
     *                        active state (disabled, deleted, etc.)
     * _________________________________________________________________________
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
  
    /**
     * =========================================================================
     * Finds user by username.
     * =========================================================================
     *
     * @param  string  $username
     *
     * @return static|null 
     * _________________________________________________________________________
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * =========================================================================
     * Finds user by email.
     * =========================================================================
     *
     * @param  string  $email
     *
     * @return static|null 
     * _________________________________________________________________________
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }    
    
    /**
     * =========================================================================
     * Finds user by password reset token.
     * =========================================================================
     *
     * @param  string  $token  Password reset token.
     *
     * @return static|null 
     * _________________________________________________________________________
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) 
        {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * =========================================================================
     * Finds out if password reset token is valid
     * =========================================================================
     *
     * @param  string  $token  Password reset token.
     *
     * @return boolean
     * _________________________________________________________________________
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) 
        {
            return false;
        }

        $expire = Yii::$app->params['user.passwordResetTokenExpire'];

        $parts = explode('_', $token);

        $timestamp = (int) end($parts);
        
        return $timestamp + $expire >= time();
    }   

    /**
     * =========================================================================
     * Finds user by account activation token.
     * =========================================================================
     *
     * @param  string  $token  Account activation token.
     *
     * @return static|null
     * _________________________________________________________________________
     */
    public static function findByAccountActivationToken($token)
    {
        return static::findOne([
            'account_activation_token' => $token,
            'status' => self::STATUS_NOT_ACTIVE,
        ]);
    }    

    /**
     * =========================================================================
     * Checks to see if the given user exists in our database.
     * If LoginForm scenario is set to lwe (login with email), we need to check 
     * user's email and password combo, otherwise we check username/password.
     * =========================================================================
     *
     * @param  string  $username  Username|email provided via login form.
     * 
     * @param  string  $password  Password provided via login form.
     *
     * @param  string  $scenario  LoginForm model scenario.
     *
     * @return object|boolean     User|false
     * _________________________________________________________________________
     */
    public static function userExists($username, $password, $scenario)
    {
        // if scenario is 'lwe', we need to check email, otherwise we check username
        $field = ($scenario === 'lwe') ? 'email' : 'username';
        
        if ($user = static::findOne([$field => $username]))
        {
            if ($user->validatePassword($password))
            {
                return $user;
            }
            else
            {
                return false; // invalid password
            }            
        }
        else
        {
            return false; // invalid username|email
        }
    }

//------------------------------------------------------------------------------------------------//
// GETTERS
//------------------------------------------------------------------------------------------------//

    /**
     * =========================================================================
     * Returns an ID that can uniquely identify a user identity
     * =========================================================================
     *
     * @return string|integer  An ID that uniquely identifies a user identity.
     * _________________________________________________________________________
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * =========================================================================
     * Returns a key that can be used to check the validity of a given 
     * identity ID. The key should be unique for each individual user, and 
     * should be persistent so that it can be used to check the validity of 
     * the user identity. The space of such keys should be big enough to defeat 
     * potential identity attacks.
     * =========================================================================
     *
     * @return string  A key that is used to check the validity of a given 
     *                 identity ID.
     * _________________________________________________________________________
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * =========================================================================
     * Returns the user status in nice format.
     * =========================================================================
     *
     * @return string
     * _________________________________________________________________________
     */
    public function getStatusName()
    {
        if ($this->status === self::STATUS_DELETED)
        {
            return "Deleted";
        } 
        elseif ($this->status === self::STATUS_NOT_ACTIVE)
        {
            return "Not active";
        }
        else
        {
            return "Active";
        }        
    }

//------------------------------------------------------------------------------------------------//
// HELPERS
//------------------------------------------------------------------------------------------------//

    /**
     * =========================================================================
     * Validates the given auth key.
     * =========================================================================
     *
     * @param  string   $authKey  The given auth key.
     *
     * @return boolean            Whether the given auth key is valid.
     * _________________________________________________________________________
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
   
    /**
     * =========================================================================
     * Validates password.
     * =========================================================================
     *
     * @param  string  $password  Password to validate.
     *
     * @return boolean            If password provided is valid for current user.
     * _________________________________________________________________________
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * =========================================================================
     * Generates password hash from password and sets it to the model.
     * =========================================================================
     *
     * @param  string  $password
     * _________________________________________________________________________
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * =========================================================================
     * Generates "remember me" authentication key. 
     * =========================================================================
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * =========================================================================
     * Generates new password reset token. 
     * =========================================================================
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * =========================================================================
     * Removes password reset token. 
     * =========================================================================
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * =========================================================================
     * Generates new account activation token. 
     * =========================================================================
     */
    public function generateAccountActivationToken()
    {
        $this->account_activation_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * =========================================================================
     * Removes account activation token. 
     * =========================================================================
     */
    public function removeAccountActivationToken()
    {
        $this->account_activation_token = null;
    }
}
