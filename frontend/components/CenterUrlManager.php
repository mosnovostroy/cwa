<?php
namespace frontend\components;

use yii\web\UrlManager;
use Yii;


class CenterUrlManager extends UrlManager
{
    public function createUrl($params)
    {
        $route = trim($params[0], '/');

        // Страница-окно авторизации через соцсеть:
		    if ($route === 'site/login')
        {
            if (isset($params['service']))
            {
                return '/login/' . $params['service'] . '/';
            }
			      return '/login/';
        }

        // Страница центра:
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

        // Страница аренды:
		    if ($route === 'arenda/view')
        {
            if (isset($params['id']))
            {
                //По id объекта определяем алиасы объявления и региона верхнего уровня:
                $alias = Yii::$app->db
                                  ->createCommand('SELECT c.alias AS `arenda_alias`, r.alias AS `region_alias`
                                      FROM arenda AS `c`, region AS `r`
                                      WHERE c.region = r.id
                                      AND c.id=:cid', [':cid' => $params['id']])
                                  ->queryOne();

                //Возвращаем урл вида arenda/moscow/romashka/
                return '/arenda/' . $alias['region_alias'] . '/' . $alias['arenda_alias'] . '/';
            }
        }

  		if (isset($params['CenterSearch']))
  		{
    			//В общем случае надо переделать GET-параметр региона в "поддиректорию" ЧПУ:
    			$region_alias = '';
    			if (isset($params['CenterSearch']['region']))
    			{
    				//По id региона определяем алиас:
    				$region_alias = Yii::$app->db
    					->createCommand('SELECT alias FROM region
    									  WHERE id=:id', [':id' => $params['CenterSearch']['region']])
    					->queryScalar();
  			  }

    			unset($params['CenterSearch']['region']);

    			//Не передаем пустые значения:
    			foreach($params['CenterSearch'] as $k => $v)
          {
    				  if (isset($params['CenterSearch'][$k]) && $params['CenterSearch'][$k] == '')
    					    unset($params['CenterSearch'][$k]);
          }
          if (isset($params['CenterSearch']['is24x7']) && $params['CenterSearch']['is24x7'] == '0')
              unset($params['CenterSearch']['is24x7']);

    			$url = parent::createUrl($params);
    			if($region_alias)
    				$url = str_replace('centers', 'centers/'.$region_alias, $url);
  		}
  		else if  (isset($params['ArendaSearch']))
  		{
    			//В общем случае надо переделать GET-параметр региона в "поддиректорию" ЧПУ:
    			$region_alias = '';
    			if (isset($params['ArendaSearch']['region']))
    			{
    				//По id региона определяем алиас:
    				$region_alias = Yii::$app->db
    					->createCommand('SELECT alias FROM region
    									  WHERE id=:id', [':id' => $params['ArendaSearch']['region']])
    					->queryScalar();
    			}

    			unset($params['ArendaSearch']['region']);

    			//Не передаем пустые значения:
    			foreach($params['ArendaSearch'] as $k => $v)
    				if (isset($params['ArendaSearch'][$k]) && $params['ArendaSearch'][$k] == '')
    					unset($params['ArendaSearch'][$k]);
    			$url = parent::createUrl($params);
    			if($region_alias)
    				$url = str_replace('arenda', 'arenda/'.$region_alias, $url);
  		}
  		else
  		{
  			  $url = parent::createUrl($params);
  		}

  		return $url;
    }

    public function parseRequest($request)
    {
        $pathInfo = $request->pathInfo;
        $params = $request->queryParams;

    		//Страница авторизации:
    		if (preg_match('%^login/$%', $pathInfo, $matches))
    		{
    			return ['site/login', $params];
    		}

    		// Страница-окно авторизации через соцсеть:
    		if (preg_match('%^login/([a-z0-9-_]+)/$%', $pathInfo, $matches))
    		{
    			$params['service'] = $matches[1];
    			return ['site/login', $params];
    		}

		    //Центр или список или карта:
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

		    //Центр или список или карта:
        if (preg_match('%^arenda/([a-z0-9-_]+)/(\S*)$%', $pathInfo, $matches))
        {
            // Два кармана после arenda:
            // matches[1] - предполагаемый алиас региона,
            // matches[2] - "остальной" хвост.

            // Сначала ищем совпадение $matches[1] с алиасом региона:
            $region_id = Yii::$app->db
                ->createCommand('SELECT id FROM region
                                  WHERE alias=:region' , [':region' => $matches[1]])
                ->queryScalar();

            //Если нашли - можно о чем-то говорить дальше:
            if ($region_id)
            {
                //Теперь сначала рассмотрим вариант, когда наша страница - страница аренды:
                if (preg_match('%([a-z0-9-_]+)/?$%', $matches[2], $detailed_matches))
                {
                    //Это так, когда в кармане $detailed_matches[1] - алиас аренды:
                    $arenda_id = Yii::$app->db
                        ->createCommand('SELECT c.id AS `cid`
                                          FROM arenda AS `c`, region AS `r`
                                          WHERE c.region = r.id
                                          AND c.alias=:arenda
                                          AND r.alias=:region' , [':arenda' => $detailed_matches[1], ':region' => $matches[1]])
                        ->queryScalar();

                    // Если таки да, это страница аренды. Настраиваем и отдаем ['arenda/view', $params]
                    if ($arenda_id)
                    {
                        $params['id'] = $arenda_id;
                        return ['arenda/view', $params];
                    }
                }

                // Если же нет (мы сюда дошли), это страница index или map или coordinates
                // c установленным регионом.
                // Настраиваем и отдаем либо ['arenda/index', $params], либо ['arenda/map', $params], либо ['arenda/coordinates', $params]
                $params['ArendaSearch']['region'] = $region_id;
                if(preg_match('%^map/\S*$%', $matches[2]))
                //if ($detailed_matches[1] === 'map')
                    return ['arenda/map', $params];
                if(preg_match('%^coordinates/\S*$%', $matches[2]))
                    return ['arenda/coordinates', $params];

                return ['arenda/index', $params];
            }
        }

        //Если "наш" код не сработал (и мы сюда таки дошли) - ладно, просто вызовем родительский метод:
        return parent::parseRequest($request);
    }
}
