<?php
namespace frontend\models;

use common\models\User;
use nenad\passwordStrength\StrengthValidator;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Password reset form.
 */
class ResetPasswordForm extends Model
{
    public $password;

    /**
     * @var \common\models\User
     */
    private $_user;

    /**
     * Creates a form model given a token.
     *
     * @param string $token  Password reset token.
     * @param array  $config Name-value pairs that will be used to initialize the object properties.
     *
     * @throws \yii\base\InvalidParamException  If token is empty or not valid.
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
     * Returns the validation rules for attributes.
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            // use passwordStrengthRule() method to determine password strength
            $this->passwordStrengthRule(),
        ];
    }

    /**
     * Set password rule based on our setting value (Force Strong Password).
     *
     * @return array Password strength rule
     */
    private function passwordStrengthRule()
    {
        // get setting value for 'Force Strong Password'
        $fsp = Yii::$app->params['fsp'];

        // password strength rule is determined by StrengthValidator 
        // presets are located in: vendor/nenad/yii2-password-strength/presets.php
        // NOTE: you should use RESET rule because it doesn't require username and email validation
        $strong = [['password'], StrengthValidator::className(), 
                    'preset'=>'reset', 'userAttribute'=>'password'];

        // use normal yii rule
        $normal = ['password', 'string', 'min' => 6];

        // if 'Force Strong Password' is set to 'true' use $strong rule, else use $normal rule
        return ($fsp) ? $strong : $normal;
    }

    /**
     * Returns the attribute labels.
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('app', 'Password'),
        ];
    }

    /**
     * Resets password.
     *
     * @return bool Whether the password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save();
    }
}
