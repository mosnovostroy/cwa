<?php

namespace frontend\controllers;

use Yii;
use common\models\Region;
use yii\web\Controller;
use common\models\RegionSearch;

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

}
