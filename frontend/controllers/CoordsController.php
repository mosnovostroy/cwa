<?php

namespace frontend\controllers;

use Yii;

use common\models\Center;

class CoordsController extends \yii\web\Controller
{
    public function actionView($region = 0, $center = 0)
    {
      header("Content-Type: application/json; charset=UTF-8");

      //Yii::info('Region: '.$region, 'myd');
      //Yii::info('Center: '.$center, 'myd');
      
      // $filer = arrray();
      // if ($center)
      //     $filter['center'] = $center;
      // if ($region)
      //     $filter['region'] = $region;
      //
      // echo Center::getCoordsJson($filter);

      if ($center)
          echo Center::getCoordsJson(['center' => $center]);
      else if ($region)
          echo Center::getCoordsJson(['region' => $region]);
      else
          echo Center::getCoordsJson();
    }
}
