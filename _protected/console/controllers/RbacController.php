<?php
namespace console\controllers;

use common\rbac\rules\OwnerRule;
use yii\console\Controller;
use Yii;

/**
 * Creates base roles and permissions for our application.
 * -----------------------------------------------------------------------------
 * Creates 4 roles: 
 * 
 * - theCreator : You, developer of this site (super admin)
 * - admin : Your direct clients, administrators of this site
 * - premium : premium member of this site
 * - member : user of this site who has registered his account and can log in
 *
 * Creates 5 permissions:
 * 
 * - useSettings : only The Creator have this permission
 * - viewUsers, deleteUsers, updateUsers, and changeRoles are 
 *   assigned to administrator of this site
 * -----------------------------------------------------------------------------
 */
class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        //---------- PERMISSIONS ----------//

        // add "useSettings" permission
        $useSettings = $auth->createPermission('useSettings');
        $useSettings->description = 'Use settings (full CRUD)';
        $auth->add($useSettings);     

        // add "viewUsers" permission
        $viewUsers = $auth->createPermission('viewUsers');
        $viewUsers->description = 'View Users';
        $auth->add($viewUsers);

        // add "deleteUsers" permission
        $deleteUsers = $auth->createPermission('deleteUsers');
        $deleteUsers->description = 'Delete users';
        $auth->add($deleteUsers);

        // add "updateUsers" permission
        $updateUsers = $auth->createPermission('updateUsers');
        $updateUsers->description = 'Update users';
        $auth->add($updateUsers);

        // add "changeRoles" permission
        $changeRoles = $auth->createPermission('changeRoles');
        $changeRoles->description = 'User can assign/change roles to other users';
        $auth->add($changeRoles); 
    
        //---------- ROLES ----------//

        // add "member" role
        $member = $auth->createRole('member');
        $member->description = 'Registered users, members of this site';
        $auth->add($member);

        // add "premium" role
        $premium = $auth->createRole('premium');
        $premium->description = 'Premium members. They have more permissions than normal members';
        $auth->add($premium);      

        // add "admin" role and give this role: 
        // viewUsers, updateUsers, deleteUsers and changeRoles permissions,
        // plus he can do everything that premium and member roles can do.
        $admin = $auth->createRole('admin');
        $admin->description = 'Administrator of this application';
        $auth->add($admin);
        $auth->addChild($admin, $viewUsers);
        $auth->addChild($admin, $updateUsers);
        $auth->addChild($admin, $deleteUsers);     
        $auth->addChild($admin, $changeRoles);
        $auth->addChild($admin, $premium);
        $auth->addChild($admin, $member);

        // add "theCreator" role ( this is you :) )
        // You can do everything that admin can do plus you can use settings :)
        $theCreator = $auth->createRole('theCreator');
        $theCreator->description = 'You!';
        $auth->add($theCreator); 
        $auth->addChild($theCreator, $useSettings);
        $auth->addChild($theCreator, $admin);
    }
}
