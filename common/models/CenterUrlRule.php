<?php

//namespace app\components;
namespace common\models;

use yii\web\UrlRuleInterface;
use yii\base\Object;
use Yii;


class CenterUrlRule extends Object implements UrlRuleInterface
{

    public function createUrl($manager, $route, $params)
    {
        if ($route === 'center/view')
        {
            if (isset($params['id']))
            {
                //По id объекта определяем алиасы центра и региона верхнего уровня:
                $alias = Yii::$app->db
                    ->createCommand('SELECT c.alias AS `center`, r.alias AS `region`
                                      FROM center AS `c`, region AS `r`
                                      WHERE c.region = r.id
                                      AND c.id=:cid', [':cid' => $params['id']])
                    ->queryOne();

                //Возвращаем урл вида centers/moscow/romashka/
                return 'centers/' . $alias['region'] . '/' . $alias['center'] . '/';
            }
        }
        //if ($route === 'center/index')
        if(0)
        {
            if (isset($params['CenterSearch']) && isset($params['CenterSearch']['region']))
            {
                Yii::info($params['CenterSearch']['region'], 'myd');

                //По id объекта определяем алиасы центра и региона верхнего уровня:
                // $alias = Yii::$app->db
                //     ->createCommand('SELECT c.alias AS `center`, r.alias AS `region`
                //                       FROM center AS `c`, region AS `r`
                //                       WHERE c.region = r.id
                //                       AND c.id=:cid', [':cid' => $params['id']])
                //     ->queryOne();

                //Возвращаем урл вида centers/moscow/romashka/
                return false; //'centers/' . $alias['region'] . '/' . $alias['center'] . '/';
            }
        }
        return false;  // данное правило не применимо
    }

    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        if (preg_match('%^centers/([a-z0-9-_]+)(/([a-z0-9-_]+)/)?$%', $pathInfo, $matches))
        {
            // Ищем совпадения $matches[1] и $matches[3] с алиасом региона и алиасом центра в базе
            $center_id = Yii::$app->db
                ->createCommand('SELECT c.id AS `cid`
                                  FROM center AS `c`, region AS `r`
                                  WHERE c.region = r.id
                                  AND c.alias=:center
                                  AND r.alias=:region' , [':center' => $matches[3], ':region' => $matches[1]])
                ->queryScalar();

            // Если нашли, устанавливаем $params['id'] и возвращаем ['center/view', $params]
            if ($center_id)
            {
                $params['id'] = $center_id;
                return ['center/view', $params];
            }
        }
        return false;  // данное правило не применимо
    }
}

?>
