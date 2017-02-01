<?php

namespace frontend\controllers;

use Yii;
use common\models\Location;
use yii\web\Controller;
use common\models\LocationSearch;
use common\models\User;
use yii\web\ForbiddenHttpException;

class LocationController extends \yii\web\Controller
{

   protected function findModel($id)
    {
        if (($model = Location::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionIndex()
    {
        $searchModel = new LocationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionCreate($region = 1)
    {
        if (!User::isAdmin())
            throw new ForbiddenHttpException;

        $model = new Location();
        if ($model->load(Yii::$app->request->post()) && $model->save() ) {
            return $this->redirect(['index', 'region' => $model->region]);
        } else {
            $model->region = $region;
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
            return $this->redirect(['index', 'region' => $model->region]);
        }
        else
            return $this->render('update', [ 'model' => $model ]);
    }


}
