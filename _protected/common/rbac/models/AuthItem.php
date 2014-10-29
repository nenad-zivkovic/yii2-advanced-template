<?php
namespace common\rbac\models;

use yii\db\ActiveRecord;
use Yii;

/**
 * -----------------------------------------------------------------------------
 * This is the model class for table "auth_item".
 *
 * @property string  $name
 * @property integer $type
 * @property string  $description
 * @property string  $rule_name
 * @property string  $data
 * @property integer $created_at
 * @property integer $updated_at
 * -----------------------------------------------------------------------------
 */
class AuthItem extends ActiveRecord
{
    /**
     * =========================================================================
     * Declares the name of the database table associated with this AR class. 
     * =========================================================================
     */
    public static function tableName()
    {
        return '{{%auth_item}}';
    }

    /**
     * =========================================================================
     * Returns roles.
     * NOTE: used for updating user role (user/update-role).
     * =========================================================================
     *
     * @return static
     * _________________________________________________________________________
     */
    public static function getRoles()
    {
        // we make sure that only The Creator can see theCreator role in drop down list
        if (Yii::$app->user->can('theCreator')) 
        {
            return static::find()->select('name')->where(['type' => 1])->all();  
        }
        // admin can not see theCreator role in drop down list
        else
        {
            return static::find()->select('name')
                                 ->where(['type' => 1])
                                 ->andWhere(['!=', 'name', 'theCreator'])
                                 ->all();
        }
    }        
}
