<?php

namespace frontend\controllers;

use Yii;
use common\models\Center;
use common\models\Tariff;
use yii\web\Controller;
use common\models\CenterSearch;
use common\models\User;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class CenterController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'update', 'delete', 'pictures', 'delete-file', 'file-set-as-anons', 'file-set-as-logo', 'features', 'create-tariff', 'update-tariff', 'delete-tariff'],
                'rules' => [
                    [
                         'actions' => ['create'],
                         'allow' => true,
                         'roles' => ['@'],
                         'matchCallback' => function ($rule, $action) {
							 return User::isAdmin();
                         }
                    ],
                    [
                         'actions' => ['update'],
                         'allow' => true,
                         'roles' => ['@'],
                         'matchCallback' => function ($rule, $action) {
							//Yii::info($action->controller->actionParams->id, 'myd');
							 return User::isAdmin();
                         }
                    ],
                    [
                         'actions' => ['features'],
                         'allow' => true,
                         'roles' => ['@'],
                         'matchCallback' => function ($rule, $action) {
							 return User::isAdmin();
                         }
                    ],
                    [
                         'actions' => ['delete'],
                         'allow' => true,
                         'roles' => ['@'],
                         'matchCallback' => function ($rule, $action) {
							 return User::isAdmin();
                         }
                    ],
                    [
                         'actions' => ['pictures'],
                         'allow' => true,
                         'roles' => ['@'],
                         'matchCallback' => function ($rule, $action) {
							 return User::isAdmin();
                         }
                    ],
                    [
                         'actions' => ['delete-file'],
                         'allow' => true,
                         'roles' => ['@'],
                         'matchCallback' => function ($rule, $action) {
							 return User::isAdmin();
                         }
                    ],
                    [
                         'actions' => ['file-set-as-anons'],
                         'allow' => true,
                         'roles' => ['@'],
                         'matchCallback' => function ($rule, $action) {
							 return User::isAdmin();
                         }
                    ],
                    [
                         'actions' => ['file-set-as-logo'],
                         'allow' => true,
                         'roles' => ['@'],
                         'matchCallback' => function ($rule, $action) {
							 return User::isAdmin();
                         }
                    ],
                    [
                         'actions' => ['features'],
                         'allow' => true,
                         'roles' => ['@'],
                         'matchCallback' => function ($rule, $action) {
							                return User::isAdmin();
                         }
                    ],
                    [
                         'actions' => ['create-tariff'],
                         'allow' => true,
                         'roles' => ['@'],
                         'matchCallback' => function ($rule, $action) {
							                return User::isAdmin();
                         }
                    ],
                    [
                         'actions' => ['update-tariff'],
                         'allow' => true,
                         'roles' => ['@'],
                         'matchCallback' => function ($rule, $action) {
							                return User::isAdmin();
                         }
                    ],
                    [
                         'actions' => ['delete-tariff'],
                         'allow' => true,
                         'roles' => ['@'],
                         'matchCallback' => function ($rule, $action) {
							                return User::isAdmin();
                         }
                    ],
                ],
            ],
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
        if (($model = Center::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionIndex()
    {
        $searchModel = new CenterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMap()
    {
        $searchModel = new CenterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$this->layout = 'map';

        return $this->render('map', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCoordinates()
    {
        $searchModel = new CenterSearch();

        header("Content-Type: application/json; charset=UTF-8");
        echo $searchModel->searchCoords(Yii::$app->request->queryParams);
    }

    public function actionIndexSubmit()
    {
        $params = Yii::$app->request->queryParams;
        $params[0] = 'center/index';
        return $this->redirect($params);
    }

    public function actionMapSubmit()
    {
        $params = Yii::$app->request->queryParams;
        $params[0] = 'center/map';
        return $this->redirect($params);
    }

    public function actionView($id)
    {
      $model = $this->findModel($id);
      return $this->render('view', [
          'model' => $model,
        ]);
    }


    /**
     * Creates a new Center model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Center();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    /**
     * Updates an existing Center model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save())
            return $this->redirect(['view', 'id' => $model->id]);
        else
            return $this->render('update', [ 'model' => $model ]);
    }

    public function actionUpdateFeatures($id)
    {
    		$center = $this->findModel($id);
        if ($center->updateFeaturesFromArray(Yii::$app->request->post()))
            return $this->redirect(['view', 'id' => $center->id ]);
        else
            return $this->render('features', ['model' => $center ]);
    }

    public function actionCreateTariff($center_id)
  	{
  		  $center = $this->findModel($center_id);
  		  $tariff = new Tariff;
        if ($tariff->load(Yii::$app->request->post()) && $center->addTariff($tariff))
            return $this->redirect(['view', 'id' => $center->id]);
  		  else
  		  {
  			    return $this->render('create-tariff', [ 'center' => $center, 'tariff' => $tariff ]);
        }
  	}

    public function actionDeleteTariff($id, $center_id)
  	{
  		$center = $this->findModel($center_id);
  		$center->deleteTariff($id);
  		return $this->redirect(['view', 'id' => $center->id]);
  	}

    public function actionUpdateTariff($id, $center_id)
    {
    		$center = $this->findModel($center_id);
        $tariff = new Tariff;
        if ($tariff->load(Yii::$app->request->post()) && $center->updateTariff($id, $tariff))
            return $this->redirect(['view', 'id' => $center->id ]);
        else
        {
            $tariff = $center->getTariffModel($id);
            return $this->render('update-tariff', ['center' => $center, 'tariff' => $tariff ]);
        }
    }

    /**
     * Deletes an existing Center model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    public function actionPictures($id)
    {
		    $model = $this->findModel($id);
        if (Yii::$app->request->isPost)
            $model->upload();
        return $this->render('pictures', ['model' => $model]);
    }

    public function actionDeleteFile($id, $filename)
    {
		    $this->findModel($id)->deleteImage($filename);
        return $this->redirect(['pictures', 'id' => $id]);
    }

    public function actionFileSetAsAnons($id, $filename)
    {
		    $this->findModel($id)->setAnonsImage($filename);
		    return $this->redirect(['pictures', 'id' => $id]);
    }
    public function actionFileSetAsLogo($id, $filename)
    {
		    $this->findModel($id)->setLogoImage($filename);
		    return $this->redirect(['pictures', 'id' => $id]);
    }
}
