<?php

namespace frontend\controllers;

use Yii;
use common\models\Event;
use yii\web\Controller;
use common\models\EventSearch;
use common\models\User;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

class EventController extends \yii\web\Controller
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
        if (($model = Event::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionIndex()
    {
        $searchModel = new EventSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexSubmit()
    {
        $params = Yii::$app->request->queryParams;
        $params[0] = 'event/index';
        return $this->redirect($params);
    }

    public function actionView($id)
    {
      $model = $this->findModel($id);

      $searchModel = new EventSearch();
      //$closestCenters = $searchModel->searchClosest($model);

      return $this->render('view', [
          'model' => $model,
          //'closestCenters' => $closestCenters,
        ]);
    }


    /**
     * Creates a new Center model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Event();
        if ($model->load(Yii::$app->request->post()) && $model->save() && $model->upload() ) {
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

        if (!User::isAdminOrOwner($model->createdBy))
            throw new ForbiddenHttpException;

        if ($model->load(Yii::$app->request->post()) && $model->save() && $model->upload() )
            return $this->redirect(['view', 'id' => $model->id]);
        else
            return $this->render('update', [ 'model' => $model ]);
    }




    /**
     * Deletes an existing Center model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
	    if (!User::isAdminOrOwner($model->createdBy))
		     throw new ForbiddenHttpException;

        $model->deleteAllImages();
        $model->deleteAllComments();
        $model->delete();
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
		    return $this->redirect(['update', 'id' => $id]);
    }
    public function actionFileSetAsLogo($id, $filename)
    {
		    $this->findModel($id)->setLogoImage($filename);
		    return $this->redirect(['pictures', 'id' => $id]);
    }
}
