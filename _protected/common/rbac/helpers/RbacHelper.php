<?php
namespace common\rbac\helpers;

use common\models\User;
use common\rbac\models\Role;
use Yii;

/**
 * RBAC helper class.
 */
class RbacHelper
{
    /**
     * In development environment we want to give theCreator role to the first signed up user.
     * This user should be You. 
     * If user is not first, there is no need to automatically give him role, his role is authenticated user '@'.
     * In case you want to give some of your custom roles to users by default, this is a good place to do it.
     *
     * @param  integer $id The id of the registered user.
     * @return boolean     True if theCreator role is assigned or if there was no need to do it.
     */
    public static function assignRole($id)
    {
        // we do not want to do any default role assignments on production, 
        // since we do not want to waste resources on user count
        if (YII_ENV_PROD) {
            return true;
        }

        // lets see how many users we got so far
        $usersCount = User::find()->count();

        // if this is not first user, we do not want to assign any custom roles to him,
        // he has the authenticated role '@' by default, so there is no need to do anything
        if ($usersCount != 1) {
            return true;
        }

        // this is first user ( you ), lets give you the theCreator role
        $auth = Yii::$app->authManager;
        $role = $auth->getRole('theCreator');
        $info = $auth->assign($role, $id);

        // if assignment was successful return true, else return false to alarm the problem
        return ($info->roleName == "theCreator") ? true : false ;
    }
}

