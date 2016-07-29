<?php

namespace frontend\controllers;

use Yii;
use common\models\Center;
use yii\web\Controller;
use common\models\CenterPictures;
use common\models\CenterSearch;
use common\models\User;
use yii\web\UploadedFile;
use yii\data\Pagination;
use common\models\Region;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class CenterController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'update', 'delete', 'pictures', 'delete-file', 'file-set-as-anons'],
                'rules' => [
                    [
                         'actions' => ['create'],
                         'allow' => true,
                         'roles' => ['@'],
                         'matchCallback' => function ($rule, $action) {
                             return User::isUserAdmin(Yii::$app->user->identity->username);
                         }
                    ],
                    [
                         'actions' => ['update'],
                         'allow' => true,
                         'roles' => ['@'],
                         'matchCallback' => function ($rule, $action) {
                             return User::isUserAdmin(Yii::$app->user->identity->username);
                         }
                    ],
                    [
                         'actions' => ['delete'],
                         'allow' => true,
                         'roles' => ['@'],
                         'matchCallback' => function ($rule, $action) {
                             return User::isUserAdmin(Yii::$app->user->identity->username);
                         }
                    ],
                    [
                         'actions' => ['pictures'],
                         'allow' => true,
                         'roles' => ['@'],
                         'matchCallback' => function ($rule, $action) {
                             return User::isUserAdmin(Yii::$app->user->identity->username);
                         }
                    ],
                    [
                         'actions' => ['delete-file'],
                         'allow' => true,
                         'roles' => ['@'],
                         'matchCallback' => function ($rule, $action) {
                             return User::isUserAdmin(Yii::$app->user->identity->username);
                         }
                    ],
                    [
                         'actions' => ['file-set-as-anons'],
                         'allow' => true,
                         'roles' => ['@'],
                         'matchCallback' => function ($rule, $action) {
                             return User::isUserAdmin(Yii::$app->user->identity->username);
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

        return $this->render('map', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexRedirect()
    {
        $params = Yii::$app->request->queryParams;
        $params[0] = 'center/index';
        return $this->redirect($params);
    }

    public function actionMapRedirect()
    {
        $params = Yii::$app->request->queryParams;
        $params[0] = 'center/map';
        return $this->redirect($params);
    }

    public function actionCoords()
    {
        $searchModel = new CenterSearch();

        header("Content-Type: application/json; charset=UTF-8");
        echo $searchModel->searchCoords(Yii::$app->request->queryParams);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $model->initPictures();
        return $this->render('view', [
            'model' => $model,
          ]);
    }

    public function actionPictures($id)
    {
        $model = new CenterPictures();
        if ($model && $model->initData($id) && Yii::$app->request->isPost) {
            $model->uploadFiles = UploadedFile::getInstances($model, 'uploadFiles');
            if ($model->upload()) {
                //return $this->redirect(['view', 'id' => $id]);
                $model->initData($id);
                return $this->render('pictures', ['model' => $model]);
            }
        }

        return $this->render('pictures', ['model' => $model]);
    }

    /**
     * Creates a new Center model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Center();
        $model->initMembers();

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
        $model->initAnonsPicture();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
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

    public function actionDeleteFile($id, $filename)
    {
        CenterPictures::deleteFile($id, $filename);
        return $this->redirect(['pictures', 'id' => $id]);
    }

    public function actionFileSetAsAnons($id, $filename)
    {
        CenterPictures::fileSetAsAnons($id, $filename);
        return $this->redirect(['pictures', 'id' => $id]);
    }

   protected function findModel($id)
    {
        if (($model = Center::findOne($id)) !== null) {
            $model->initMembers();
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
