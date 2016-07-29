<?php
namespace frontend\components;

use yii\web\UrlManager;
use Yii;


class CenterUrlManager extends UrlManager
{
    public function createUrl($params)
    {
        if (isset($params['CenterSearch']) && isset($params['CenterSearch']['region']))
        {
            //По id региона определяем алиас:
            $alias = Yii::$app->db
                ->createCommand('SELECT alias FROM region
                                  WHERE id=:id', [':id' => $params['CenterSearch']['region']])
                ->queryScalar();

            if($alias)
            {
                //Переделываем GET-параметр CenterSearch[region] в "поддиректорию"
                unset($params['CenterSearch']['region']);
                unset($params['CenterSearch']);
                $url = parent::createUrl($params);
                //$url = $url.'/'.$alias;
                $url = str_replace('centers', 'centers/'.$alias, $url);
                return $url;
            }
        }

        return parent::createUrl($params);
    }
}
