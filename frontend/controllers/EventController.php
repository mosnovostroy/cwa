<?php

namespace frontend\controllers;

use Yii;
use common\models\News;
use common\models\NewsSearch;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class EventController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

   protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionIndex()
    {
        $searchModel = new NewsSearch();        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
      $model = $this->findModel($id);

      if (!$model->eventAt ) {
          throw new NotFoundHttpException('The requested page does not exist.');
      }

      return $this->render('view', [
          'model' => $model,
        ]);
    }
}
