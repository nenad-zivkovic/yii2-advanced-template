<?php
namespace app\models;

use nenad\passwordStrength\StrengthValidator;
use app\rbac\helpers\RbacHelper;
use app\models\Setting;
use app\models\User;
use yii\base\Model;
use Yii;

/**
 * -----------------------------------------------------------------------------
 * Signup form.
 * -----------------------------------------------------------------------------
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $status;

    /**
     * =========================================================================
     * Returns the validation rules for attributes.
     * =========================================================================
     */
    public function rules()
    {
        // get setting value for 'Force Strong Password'
        $fsp = Setting::get(Setting::FORCE_STRONG_PASSWORD);

        // use StrengthValidator rule (presets are located in: widgets/passwordStrenght/presets.php)
        $strong = [['password'], StrengthValidator::className(), 'preset'=>'normal', 
                                                                 'userAttribute'=>'username'];
        // use normal yii rule
        $normal = ['password', 'string', 'min' => 6];

        // if 'Force Strong Password' is set to 'YES' use $strong rule, else usee $normal rule
        $passwordStrenghtRule = ($fsp) ? $strong : $normal;

        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['username', 'unique', 'targetClass' => '\app\models\User', 
                'message' => 'This username has already been taken.'],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\app\models\User', 
                'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            // dinamicaly decide which password rule to use
            $passwordStrenghtRule,

            // on default scenario, user status is set to active
            ['status', 'default', 'value' => User::STATUS_ACTIVE, 'on' => 'default'],
            // status is set to not active on rna (registration needs activation) scenario
            ['status', 'default', 'value' => User::STATUS_NOT_ACTIVE, 'on' => 'rna'],
            // status has to be integer value in the given range. Check User model.
            ['status', 'in', 'range' => [User::STATUS_NOT_ACTIVE, User::STATUS_ACTIVE]]
        ];
    }

    /**
     * =========================================================================
     * Signs up the user. 
     * If scenario is set to "rna" (registration needs activation), this means 
     * that user need to activate his account using email confirmation method.
     * =========================================================================
     *
     * @return User|null              The saved model or null if saving fails.
     * _________________________________________________________________________
     */
    public function signup()
    {
        $user = new User();

        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->status = $this->status;

        // if scenario is "rna" we will generate account activation token
        $this->scenario === 'rna' ? $user->generateAccountActivationToken() : '';
  
        if ($user->save() && RbacHelper::assignRole($user->getId())) 
        {
            return $user;
        } 
        else
        {
            return null;
        }
    }

    /**
     * =========================================================================
     * Sends email to registered user with account activation link.
     * =========================================================================
     *
     * @param  object   $user  Registered user.
     * 
     * @return boolean         Whether the message has been sent successfully.
     * _________________________________________________________________________
     */
    public function sendAccountActivationEmail($user)
    {
        return Yii::$app->mailer->compose('accountActivationToken', ['user' => $user])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account activation for ' . Yii::$app->name)
            ->send();
    }
}
