<?php
namespace common\rbac\models;

use common\models\User;
use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "auth_assignment".
 *
 * @property string $item_name
 * @property string $user_id
 * @property integer $created_at
 *
 * @property $username User
 */
class Role extends ActiveRecord
{
    /**
     * Declares the name of the database table associated with this AR class.
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%auth_assignment}}';
    }

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['item_name'], 'required'],
            [['item_name'], 'string', 'max' => 64],
        ];
    }

    /**
     * Returns the attribute labels.
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'item_name' => Yii::t('app', 'Role'),
        ];
    }

    /**
     * Relation with User model.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        // Role has_many User via User.id -> user_id
        return $this->hasMany(User::className(), ['id' => 'user_id']);
    }
}
