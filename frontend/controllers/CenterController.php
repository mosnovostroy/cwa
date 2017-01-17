<?php

namespace frontend\controllers;

use Yii;
use common\models\Center;
use common\models\News;
use common\models\Tariff;
use yii\web\Controller;
use common\models\CenterSearch;
use common\models\NewsSearch;
use common\models\User;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use common\models\Region;

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
        $params = Yii::$app->request->queryParams;

        $searchModel = new CenterSearch();

        $dataProvider = $searchModel->search($params);
        //Yii::info(Yii::$app->request->queryParams, 'myd');
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'metro' => isset($params['metro']) ? $params['metro'] : null,
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

      $searchModel = new CenterSearch();
      $closestCenters = $searchModel->searchClosest($model);

      $newsModel = new NewsSearch();
      $news = $newsModel->searchForCenter($id, 3);

      return $this->render('view', [
          'model' => $model,
          'closestCenters' => $closestCenters,
          'news' => $news,
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
            $model->initMapParams();
            return $this->render('create', [ 'model' => $model ]);
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
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [ 'model' => $model ]);
        }
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

    // public function beforeAction($action)
    // {
    //     $toRedir = [
    //         'index' => 'index1',
    //     ];
    //
    //     if (isset($toRedir[$action->id])) {
    //         Yii::$app->response->redirect(Url::to(['centers/index2']), 301);
    //         Yii::$app->end();
    //     }
    //     return parent::beforeAction($action);
    // }

    public function actionDeleteNewsLink($center_id, $news_id)
    {
        // Получаем AR новости и центра:
        $center = $this->findModel($center_id);
        $news = News::findOne($news_id);

        // Удаляем запись из промежуточной таблицы:
        $center->unlink('news', $news, true);

        // Возвращаем обновленный фрагмент html:
        return $this->renderAjax('_news', ['model' => $center]);
    }


    public function actionNewsList($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new \yii\db\Query;
            $query->select('id, title AS text')
                ->from('news')
                ->where(['like', 'title', $q])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => News::find($id)->name];
        }
        return $out;
    }

    public function actionAddNewsLink($center_id, $news_id)
    {
        // Получаем AR новости и центра:
        $center = $this->findModel($center_id);
        $news = News::findOne($news_id);

        // Удаляем запись из промежуточной таблицы:
        $center->link('news', $news);

        // Возвращаем обновленный фрагмент html:
        return $this->renderAjax('_news', ['model' => $center]);
    }

}
