<?php
namespace console\controllers;

use yii\console\Controller;
use Yii;

/**
 * Creates base roles and permissions for our application.
 * -----------------------------------------------------------------------------
 * Creates 5 roles:
 *
 * - theCreator : You, developer of this site (super admin)
 * - admin : Your direct clients, administrators of this site
 * - support : support staff
 * - premium : premium member of this site
 * - member : user of this site who has registered his account and can log in
 *
 * Creates 2 permissions:
 *
 * - usePremiumContent : allows premium members to use premium content
 * - manageUsers : allows admins to manage users (CRUD plus role assignment)
 *
 * @package console\controllers
 */
class RbacController extends Controller
{
    /**
     * Initializes the RBAC authorization data.
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        //---------- PERMISSIONS ----------//

        // add "usePremiumContent" permission
        $usePremiumContent = $auth->createPermission('usePremiumContent');
        $usePremiumContent->description = 'View Users';
        $auth->add($usePremiumContent);

        // add "manageUsers" permission
        $manageUsers = $auth->createPermission('manageUsers');
        $manageUsers->description = 'View Users';
        $auth->add($manageUsers);
    
        //---------- ROLES ----------//

        // add "member" role
        $member = $auth->createRole('member');
        $member->description = 'Registered users, members of this site';
        $auth->add($member);

        // add "premium" role
        $premium = $auth->createRole('premium');
        $premium->description = 'Premium members. They have more permissions than normal members';
        $auth->add($premium);
        $auth->addChild($premium, $usePremiumContent);

        // add "support" role
        // support can do everything that member and premium can, plus you can add him more powers
        $support = $auth->createRole('support');
        $support->description = 'Support staff';
        $auth->add($support); 
        $auth->addChild($support, $premium);
        $auth->addChild($support, $member);    

        // add "admin" role and give this role: 
        // manageUsers permission, plus he can do everything that support role can do.
        $admin = $auth->createRole('admin');
        $admin->description = 'Administrator of this application';
        $auth->add($admin);
        $auth->addChild($admin, $manageUsers);
        $auth->addChild($admin, $support);

        // add "theCreator" role ( this is you :) )
        // You can do everything that admin can do plus more (if You decide so)
        $theCreator = $auth->createRole('theCreator');
        $theCreator->description = 'You!';
        $auth->add($theCreator); 
        $auth->addChild($theCreator, $admin);

        if ($auth) 
        {
            echo "\nRbac authorization data were installed successfully.\n";
        }
    }
}