<?php

namespace frontend\models;

use Yii;
use common\behaviors\RegionInfoBehavior;

class Index extends \yii\base\Model
{
	public $type = 1;
	public $region = 0;
	
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
            [['type'], 'integer'],
            [['region'], 'integer'],
        ];
    }
}
