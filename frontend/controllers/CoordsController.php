<?php

namespace frontend\controllers;

use Yii;

use common\models\CenterSearch;

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

      /*if ($center)
          echo CenterSearch::getCoordsJson(['center' => $center]);
      else if ($region)
          echo CenterSearch::getCoordsJson(['region' => $region]);
      else
          echo CenterSearch::getCoordsJson();*/
    }
}
