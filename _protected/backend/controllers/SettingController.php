<?php
namespace backend\controllers;

use common\models\Setting;
use yii\data\Pagination;
use yii\data\Sort;
use yii\web\NotFoundHttpException;
use Yii;

/**
 * -----------------------------------------------------------------------------
 * SettingController implements the CRUD actions for Setting model.
 * -----------------------------------------------------------------------------
 */
class SettingController extends BackendController
{
    /**
     * How many settings we want to display per page.
     * 
     * @var integer
     */
    private $_pageSize = 12;

    /**
     * =========================================================================
     * Lists all Settings.
     * =========================================================================
     * 
     * @return mixed
     * _________________________________________________________________________
     */
    public function actionIndex()
    {
        $model = Setting::find();

        $pagination = new Pagination(['totalCount' => $model->count(),
                                      'pageSize' => $this->_pageSize]);

        $sort = new Sort(['attributes' => ['id', 'name', 'value']]);

        $settings = $model->offset($pagination->offset)
                          ->orderBy($sort->orders)
                          ->limit($pagination->limit)
                          ->all();

        return $this->render('index', [
            'settings' => $settings,
            'pagination' => $pagination,
            'sort' => $sort,
        ]);
    }

    /**
     * =========================================================================
     * Creates a new Setting model. If creation is successful, 
     * the browser will be redirected to the 'index' page.
     * =========================================================================
     * 
     * @return mixed
     * _________________________________________________________________________
     */
    public function actionCreate()
    {
        $model = new Setting();

        if ($model->load(Yii::$app->request->post()) && $model->save()) 
        {
            return $this->redirect('index');
        }
        else 
        {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * =========================================================================
     * Updates an existing Setting model. If update is successful, 
     * the browser will be redirected to the 'index' page.
     * =========================================================================
     *
     * @param  integer  $id
     *
     * @return mixed
     * _________________________________________________________________________
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) 
        {
            return $this->redirect('index');
        } 
        else 
        {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * =========================================================================
     * Deletes an existing Setting model. If deletion is successful, 
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

        return $this->redirect(['index']);
    }

    /**
     * =========================================================================
     * Finds the Setting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * =========================================================================
     *
     * @param  integer  $id
     *
     * @return Settings the loaded model.
     *
     * @throws NotFoundHttpException if the model cannot be found.
     * _________________________________________________________________________
     */
    protected function findModel($id)
    {
        if (($model = Setting::findOne($id)) !== null) 
        {
            return $model;
        } 
        else 
        {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
