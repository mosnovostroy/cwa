<?php

namespace frontend\controllers;

use Yii;
use common\models\Item;
use yii\web\Controller;
use common\models\ItemSearch;
use common\models\Fastsearch;
use common\models\User;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\db\Query;
use yii\web\ForbiddenHttpException;

class SearchController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $searchModel = new ItemSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexSubmit()
    {
        $params = Yii::$app->request->queryParams;
        $params[0] = 'search/index';
        return $this->redirect($params);
    }

    public function actionItemsList($q = null)
    {
        $model = new Fastsearch();
        echo $model->search($q);
    }

}
