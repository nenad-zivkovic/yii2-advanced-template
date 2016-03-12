<?php
namespace common\helpers;

use common\models\User;
use Yii;

/**
 * Css helper class.
 */
class CssHelper
{
    /**
     * Returns the appropriate css class based on the value of user $status.
     * NOTE: used in user/index view.
     *
     * @param  string $status User status.
     * @return string         Css class.
     */
    public static function userStatusCss($status)
    {
        if ($status !== User::STATUS_ACTIVE) {
            return "boolean-false";
        }

        return "boolean-true";     
    }

    /**
     * Returns the appropriate css class based on the value of role $item_name.
     * NOTE: used in user/index view.
     *
     * @param  string $role Role name.
     * @return string       Css class.
     */
    public static function roleCss($role)
    {
        return "role-".$role."";    
    }
}