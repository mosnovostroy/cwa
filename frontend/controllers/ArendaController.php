<?php

namespace frontend\controllers;

use Yii;
use common\models\Arenda;
use yii\web\Controller;
use common\models\ArendaSearch;
use common\models\User;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

class ArendaController extends \yii\web\Controller
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
                    ],
                    [
                         'actions' => ['update'],
                         'allow' => true,
                         'roles' => ['@'],
                    ],
                    [
                         'actions' => ['delete'],
                         'allow' => true,
                         'roles' => ['@'],
                    ],
                    [
                         'actions' => ['pictures'],
                         'allow' => true,
                         'roles' => ['@'],
                    ],
                    [
                         'actions' => ['delete-file'],
                         'allow' => true,
                         'roles' => ['@'],
                    ],
                    [
                         'actions' => ['file-set-as-anons'],
                         'allow' => true,
                         'roles' => ['@'],
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
        if (($model = Arenda::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Actions for everyone (guests and users):
    public function actionIndex()
    {
        $searchModel = new ArendaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMap()
    {
        $searchModel = new ArendaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$this->layout = 'map';

        return $this->render('map', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCoordinates()
    {
        $searchModel = new ArendaSearch();

        header("Content-Type: application/json; charset=UTF-8");
        echo $searchModel->searchCoords(Yii::$app->request->queryParams);
    }

    public function actionIndexSubmit()
    {
        $params = Yii::$app->request->queryParams;
        $params[0] = 'arenda/index';
        return $this->redirect($params);
    }

    public function actionMapSubmit()
    {
        $params = Yii::$app->request->queryParams;
        $params[0] = 'arenda/map';
        return $this->redirect($params);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
          ]);
    }

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Actions for authorized:

    /**
     * Creates a new Arenda model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Arenda();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->createAlias();
            return $this->redirect(['site/my']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Arenda model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$model = $this->findModel($id);
		if (!User::isAdminOrOwner($model->createdBy))
			throw new ForbiddenHttpException;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['site/my']);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
    }

    /**
     * Deletes an existing Arenda model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
		if (!User::isAdminOrOwner($model->createdBy))
			throw new ForbiddenHttpException;
		$model->delete();
        return $this->redirect(['site/my']);
    }

    public function actionPictures($id)
    {
		$model = $this->findModel($id);
		if (!User::isAdminOrOwner($model->createdBy))
			throw new ForbiddenHttpException;
        if (Yii::$app->request->isPost)
            $model->upload();
        return $this->render('pictures', ['model' => $model]);
    }

    public function actionDeleteFile($id, $filename)
    {
		$model = $this->findModel($id);
		if (!User::isAdminOrOwner($model->createdBy))
			throw new ForbiddenHttpException;
		$model->deleteImage($filename);
        return $this->redirect(['pictures', 'id' => $id]);
    }

    public function actionFileSetAsAnons($id, $filename)
    {
		$model = $this->findModel($id);
		if (!User::isAdminOrOwner($model->createdBy))
			throw new ForbiddenHttpException;
		$model->setAnonsImage($filename);
		return $this->redirect(['pictures', 'id' => $id]);
    }
}
