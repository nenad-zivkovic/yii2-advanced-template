<?php
namespace console\controllers;

use common\rbac\rules\AuthorRule;
use yii\helpers\Console;
use yii\console\Controller;
use Yii;

/**
 * Creates base RBAC authorization data for our application.
 * -----------------------------------------------------------------------------
 * Creates 5 roles:
 *
 * - theCreator : you, developer of this site (super admin)
 * - admin      : your direct clients, administrators of this site
 * - employee   : employee of this site / company, this may be someone who should not have admin rights
 * - premium    : premium member of this site (authenticated users with extra powers)
 * - member     : authenticated user, this role is equal to default '@', and it does not have to be set upon sign up
 *
 * Creates 2 permissions:
 *
 * - usePremiumContent  : allows premium users to use premium content
 * - manageUsers        : allows admin+ roles to manage users (CRUD plus role assignment)
 *
 * Creates 1 rule:
 *
 * - AuthorRule : allows employee+ roles to update their own content (not used by default)
 */
class RbacController extends Controller
{
    /**
     * Initializes the RBAC authorization data.
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        //---------- RULES ----------//

        // add the rule (not used by default)
        $rule = new AuthorRule;
        $auth->add($rule);

        //---------- PERMISSIONS ----------//

        // add "usePremiumContent" permission
        $usePremiumContent = $auth->createPermission('usePremiumContent');
        $usePremiumContent->description = 'Allows premium+ roles to use premium content';
        $auth->add($usePremiumContent);

        // add "manageUsers" permission
        $manageUsers = $auth->createPermission('manageUsers');
        $manageUsers->description = 'Allows admin+ roles to manage users';
        $auth->add($manageUsers);

        //---------- ROLES ----------//

        // add "member" role
        $member = $auth->createRole('member');
        $member->description = 'Authenticated user, equal to "@"';
        $auth->add($member); 

        // add "premium" role
        $premium = $auth->createRole('premium');
        $premium->description = 'Premium users. Authenticated users with extra powers';
        $auth->add($premium); 
        $auth->addChild($premium, $member);
        $auth->addChild($premium, $usePremiumContent);

        // add "employee" role and give this role: 
        // createArticle, updateOwnArticle and adminArticle permissions, plus premium role.
        $employee = $auth->createRole('employee');
        $employee->description = 'Employee of this site/company who has lower rights than admin';
        $auth->add($employee);
        $auth->addChild($employee, $premium);

        // add "admin" role and give this role: 
        // manageUsers, updateArticle adn deleteArticle permissions, plus employee role.
        $admin = $auth->createRole('admin');
        $admin->description = 'Administrator of this application';
        $auth->add($admin);
        $auth->addChild($admin, $employee);
        $auth->addChild($admin, $manageUsers);

        // add "theCreator" role ( this is you :) )
        // You can do everything that admin can do plus more (if You decide so)
        $theCreator = $auth->createRole('theCreator');
        $theCreator->description = 'You!';
        $auth->add($theCreator); 
        $auth->addChild($theCreator, $admin);

        if ($auth) {
            $this->stdout("\nRbac authorization data are installed successfully.\n", Console::FG_GREEN);
        }
    }
}