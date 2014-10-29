<?php
namespace backend\controllers;

use backend\controllers\BackendController;
use common\models\User;
use common\rbac\models\Role;
use yii\data\Pagination;
use yii\data\Sort;
use yii\web\NotFoundHttpException;
use Yii;

/**
 * -----------------------------------------------------------------------------
 * UserController displays users and provides: 
 * user update (own account), delete and role update actions.
 * -----------------------------------------------------------------------------
 */
class UserController extends BackendController
{
    /**
     * How many users we want to display per page.
     * 
     * @var integer
     */
    private $_pageSize = 12;

    /**
     * =========================================================================
     * Lists all User models and provides sorting and pagination.
     * Admin will not be able to see The Creator.
     * =========================================================================
     *
     * @return mixed
     * _________________________________________________________________________
     */
    public function actionIndex()
    {    
        $model = User::find();

        $pagination = new Pagination(['totalCount' => $model->count(),
                                      'pageSize' => $this->_pageSize]);

        $sort = new Sort(['attributes' => ['id', 'username', 'email', 'status', 
                                           'item_name' => ['label' => 'Role']]]);

        // we make sure that admin can not see users with theCreator role
        if (!Yii::$app->user->can('theCreator')) 
        {
            $model = User::find()->where(['!=', 'item_name', 'theCreator']);
        }

        $users = $model->select('id, username, email, status')
                       ->joinWith('role')
                       ->orderBy($sort->orders)
                       ->limit($pagination->limit)
                       ->offset($pagination->offset)
                       ->all();

        return $this->render('index', [
            'users' => $users,
            'pagination' => $pagination,
            'sort' => $sort,
        ]);
    }

    /**
     * =========================================================================
     * Allows admin/The Creator to update his account. If update is successful, 
     * the browser will be redirected to the 'index' page.
     * =========================================================================
     *
     * @param  integer  $id  User id.
     *
     * @return mixed
     * _________________________________________________________________________
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // we allow admin/The Creator to update only his own account
        if ($model->id !== Yii::$app->user->id) 
        {
            return $this->goHome();
        }

        if ($model->load(Yii::$app->request->post())) 
        {
            // only if user entered new password we want to hash and save it
            if ($model->newPassword) 
            {
                $model->password = $model->newPassword;
            }
            
            if ($model->save()) 
            {
                return $this->redirect('index');
            }
        } 

        return $this->render('update', [
            'model' => $model,
        ]); 
    }    

    /**
     * =========================================================================
     * Deletes an existing User model and his role. If deletion is successful, 
     * the browser will be redirected to the 'index' page.
     * =========================================================================
     *
     * @param  integer $id
     *
     * @return mixed
     * _________________________________________________________________________
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        // delete this user's role from auth_assignment table
        if ($role = Role::find()->where(['user_id'=>$id])->one()) 
        {
            $role->delete();
        }
        
        return $this->redirect(['index']);
    }

    /**
     * =========================================================================
     * Updates user role.
     * =========================================================================
     *
     * @param  integer  $id  User id.
     *
     * @return mixed
     *
     * @throws NotFoundHttpException if the model cannot be found.
     * _________________________________________________________________________
     */
    public function actionUpdateRole($id)
    {
        // only The Creator can update everyones roles
        if (Yii::$app->user->can('theCreator')) 
        {
            $model = Role::findOne(['user_id' => $id]);
        }
        // admin can not update The Creators role
        else
        {
            $model = Role::find()->where(['user_id' => $id])
                                 ->andWhere(['!=', 'item_name', 'theCreator'])
                                 ->one();
        }

        // do update if everything is OK
        if ($model !== null) 
        {
            if ($model->load(Yii::$app->request->post()) && $model->save())
            {
                return $this->redirect(['user/index']);
            } 
            else 
            {
                return $this->render('update-role', [
                    'model' => $model,
                ]);
            }
        } 
        else 
        {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }    

    /**
     * =========================================================================
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * =========================================================================
     *
     * @param  integer $id
     *
     * @return User the loaded model.
     *
     * @throws NotFoundHttpException if the model cannot be found.
     * _________________________________________________________________________
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) 
        {
            return $model;
        } 
        else 
        {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
