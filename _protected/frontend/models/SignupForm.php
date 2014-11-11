<?php
namespace frontend\models;

use nenad\passwordStrength\StrengthValidator;
use common\rbac\helpers\RbacHelper;
use common\models\Setting;
use common\models\User;
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
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['username', 'unique', 'targetClass' => '\common\models\User', 
                'message' => 'This username has already been taken.'],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 
                'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            // decide which password rule to use based on our system settings
            $this->passwordStrengthRule(),

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
        if ($this->scenario === 'rna')
        {
            $user->generateAccountActivationToken();
        }

        // if user is saved and role is assigned return user object
        return $user->save() && RbacHelper::assignRole($user->getId()) ? $user : null;
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

    /**
     * =========================================================================
     * Set password rule based on our setting value (Force Strong Password).
     * =========================================================================
     * 
     * @return array  Password strength rule
     * _________________________________________________________________________
     */
    private function passwordStrengthRule()
    {
        // get setting value for 'Force Strong Password'
        $fsp = Setting::get(Setting::FORCE_STRONG_PASSWORD);

        // use StrengthValidator rule 
        // presets are located in: vendor/nenad/yii2-password-strength/presets.php
        $strong = [['password'], StrengthValidator::className(), 'preset'=>'normal'];

        // use normal yii rule
        $normal = ['password', 'string', 'min' => 6];

        // if 'Force Strong Password' is set to 'YES' use $strong rule, else use $normal rule
        return ($fsp) ? $strong : $normal;
    }
}
