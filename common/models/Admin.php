<?php

namespace common\models;

use Yii;
use common\behaviors\RegionInfoBehavior;

class Admin extends \yii\base\Model
{

    public function behaviors()
    {
        return [
            'regionInfo' => [
                'class' => RegionInfoBehavior::className(),
            ],
        ];
    }

    public function rules()
    {
        return [
        ];
    }
}
