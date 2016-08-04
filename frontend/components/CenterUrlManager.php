<?php
namespace frontend\components;

use yii\web\UrlManager;
use Yii;


class CenterUrlManager extends UrlManager
{
    public function createUrl($params)
    {
        // Страница центра:
        $route = trim($params[0], '/');
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
                return '/centers/' . $alias['region'] . '/' . $alias['center'] . '/';
            }
        }

        //В общем случае надо переделать GET-параметр региона в "поддиректорию" ЧПУ:
        $region_alias = '';
        if (isset($params['CenterSearch']) && isset($params['CenterSearch']['region']))
        {
            //По id региона определяем алиас:
            $region_alias = Yii::$app->db
                ->createCommand('SELECT alias FROM region
                                  WHERE id=:id', [':id' => $params['CenterSearch']['region']])
                ->queryScalar();
        }

        unset($params['CenterSearch']['region']);

        //Не передаем пустые значения:
        if (isset($params['CenterSearch']))
            foreach($params['CenterSearch'] as $k => $v)
                if (isset($params['CenterSearch'][$k]) && $params['CenterSearch'][$k] == '')
                    unset($params['CenterSearch'][$k]);
        $url = parent::createUrl($params);
        if($region_alias)
            $url = str_replace('centers', 'centers/'.$region_alias, $url);

        return $url;
    }

    public function parseRequest($request)
    {
        // Yii::info($request->pathInfo, 'myd');
        // Yii::info($request->queryString, 'myd');
        // Yii::info($request->queryParams, 'myd');

        $pathInfo = $request->pathInfo;
        $params = $request->queryParams;
        if (preg_match('%^centers/([a-z0-9-_]+)/(\S*)$%', $pathInfo, $matches))
        {
            // Два кармана после centers:
            // matches[1] - предполагаемый алиас региона,
            // matches[2] - "остальной" хвост.

            // Сначала ищем совпадение $matches[1] с алиасом региона:
            $region_id = Yii::$app->db
                ->createCommand('SELECT id FROM region
                                  WHERE alias=:region' , [':region' => $matches[1]])
                ->queryScalar();

            // Yii::info($pathInfo, 'myd');
            // Yii::info($matches, 'myd');
            // Yii::info($region_id, 'myd');

            //Если нашли - можно о чем-то говорить дальше:
            if ($region_id)
            {
                //Теперь сначала рассмотрим вариант, когда наша страница - страница центра:
                if (preg_match('%([a-z0-9-_]+)/?$%', $matches[2], $detailed_matches))
                {
                    //Это так, когда в кармане $detailed_matches[1] - алиас центра:
                    $center_id = Yii::$app->db
                        ->createCommand('SELECT c.id AS `cid`
                                          FROM center AS `c`, region AS `r`
                                          WHERE c.region = r.id
                                          AND c.alias=:center
                                          AND r.alias=:region' , [':center' => $detailed_matches[1], ':region' => $matches[1]])
                        ->queryScalar();

                    //Yii::info('Center: '.$center_id, 'myd');

                    // Если таки да, это страница центра. Настраиваем и отдаем ['center/view', $params]
                    if ($center_id)
                    {
                        $params['id'] = $center_id;
                        return ['center/view', $params];
                    }
                }

                // Если же нет (мы сюда дошли), это страница index или map или coordinates
                // c установленным регионом.
                // Настраиваем и отдаем либо ['center/index', $params], либо ['center/map', $params], либо ['center/coordinates', $params]
                $params['CenterSearch']['region'] = $region_id;
                if(preg_match('%^map/\S*$%', $matches[2]))
                //if ($detailed_matches[1] === 'map')
                    return ['center/map', $params];
                if(preg_match('%^coordinates/\S*$%', $matches[2]))
                    return ['center/coordinates', $params];

                return ['center/index', $params];
            }
        }
        //Если "наш" код не сработал (и мы сюда таки дошли) - ладно, просто вызовем родительский метод:
        return parent::parseRequest($request);
    }
}
