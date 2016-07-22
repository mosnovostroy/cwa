<?php

namespace frontend\controllers;

use common\models\Center;

class CoordsController extends \yii\web\Controller
{
    public function actionView($id = 0)
    {
      header("Content-Type: application/json; charset=UTF-8");
      echo Center::getCoordsJson($id);
    }
}
