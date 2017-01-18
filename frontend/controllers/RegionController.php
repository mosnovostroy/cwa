<?php

namespace frontend\controllers;

use Yii;
use common\models\Region;
use yii\web\Controller;
use common\models\RegionSearch;
use common\models\User;
use yii\web\ForbiddenHttpException;

class RegionController extends \yii\web\Controller
{

   protected function findModel($id)
    {
        if (($model = Region::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionIndex()
    {
        $searchModel = new RegionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionCreate()
    {
        if (!User::isAdmin())
            throw new ForbiddenHttpException;

        $model = new Region();
        if ($model->load(Yii::$app->request->post()) && $model->save() ) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    public function actionUpdate($id)
    {
        if (!User::isAdmin())
            throw new ForbiddenHttpException;

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save() ) {
            return $this->redirect(['index']);
        }
        else
            return $this->render('update', [ 'model' => $model ]);
    }


}
