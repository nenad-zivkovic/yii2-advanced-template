<?php
namespace common\models;

use yii\db\ActiveRecord;
use Yii;

/**
 * -----------------------------------------------------------------------------
 * This is the model class for table "settings".
 *
 * @property integer $id
 * @property string $name
 * @property integer $value
 * -----------------------------------------------------------------------------
 */
class Setting extends ActiveRecord
{
    //-- setting name and it's id value --//
    const REGISTRATION_NEEDS_ACTIVATION = 1;
    const LOGIN_WITH_EMAIL = 2;

    /**
     * =========================================================================
     * Declares the name of the database table associated with this AR class. 
     * =========================================================================
     */
    public static function tableName()
    {
        return '{{%setting}}';
    }

    /**
     * =========================================================================
     * Returns the validation rules for attributes.
     * =========================================================================
     */
    public function rules()
    {
        return [
            [['name', 'value'], 'required'],
            [['value'], 'integer'],
            [['name'], 'string', 'max' => 255],
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
            'id' => 'ID',
            'name' => 'Name',
            'value' => 'Value',
        ];
    }

    /**
     * =========================================================================
     * Get setting value for the specified setting id.
     * =========================================================================
     *
     * @param  integer  $id  Setting id.
     *
     * @return integer|null  True/false (1/0).
     * _________________________________________________________________________
     */
    public static function get($id)
    {
        $setting = static::findOne(['id' => $id]);

        if ($setting) 
        {
            return $setting->value;
        }
        else 
        {
            return 0; // if setting do not exists, fallback to default scenario
        } 
    }
}
