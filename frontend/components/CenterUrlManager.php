<?php
namespace frontend\components;

use yii\web\UrlManager;
use Yii;
use yii\helpers\Url;
use common\models\Station;
use common\models\Center;
use common\models\Arenda;

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

        // Страница "О проекте":
		if ($route === 'site/about')
        {
			return '/about/';
        }

        // Страница "Контакты":
		if ($route === 'site/contacts')
        {
			return '/contacts/';
        }

        // Страница "Реклама":
		if ($route === 'site/adv')
        {
			return '/adv/';
        }

        // Страница "Реклама":
		if ($route === 'site/updatemetro')
        {
			return '/updatemetro/';
        }

        // Страница "Регионы":
		if ($route === 'region/index')
        {
			return '/regions/';
        }

        // Страница "Администирование":
		if ($route === 'site/admin')
        {
			return '/admin/';
        }

        // Страница центра:
		if ($route === 'center/view')
        {
            if (isset($params['id']) && $params['id'] > 0)
            {
                $center = Center::findOne($params['id']);
                if ($center) {
                    return '/' . $center->regionData->alias. '/centers/' . $center->alias . '/';
                }
            }
        }

        // Страница объявления:
		if ($route === 'arenda/view')
        {
            if (isset($params['id']) && $params['id'] > 0)
            {
                $center = Arenda::findOne($params['id']);
                if ($center) {
                    return '/' . $center->regionData->alias. '/arenda/' . $center->alias . '/';
                }
            }
        }

        // Страница новости:
		if ($route === 'news/view')
        {
            if (isset($params['id']))
            {
                //Возвращаем урл вида /news/104/
                return '/news/' . $params['id'] . '/';
            }
        }

        // Страница мероприятия:
		if ($route === 'events/view')
        {
            if (isset($params['id']))
            {
                //Возвращаем урл вида /events/104/
                return '/events/' . $params['id'] . '/';
            }
        }

        // Главная страница (возможно, с выставленным регионом):
		if ($route === 'site/index')
        {
            if (isset($params['region']))
            {
                //По id объекта определяем алиасы центра и региона верхнего уровня:
                $alias = Yii::$app->db
                                  ->createCommand('SELECT alias FROM region WHERE id=:rid',
                                      [':rid' => $params['region']])
                                  ->queryScalar();

                //Возвращаем урл вида /moscow/
                return '/' . $alias . '/';
            }
        }

        // Страницы с результатами поиска (списки и карты): на них влияет установленный в сессии регион
        $url = parent::createUrl($params);
        if ($route == 'center/index' ||
            $route == 'news/index' ||
            $route == 'event/index' ||
            $route == 'arenda/index' ||
            $route == 'center/map' ||
            $route == 'arenda/map'
            )
        {
            $regionId = Yii::$app->regionManager->id;

            // Если регион пришел в параметрах, у него приоритет над сессионным:
            if (isset($params['region'])) {
                $regionId = $params['region'];
            }

            // Если это список новостей коворкинга, регион этого коворкинга также в приоритете:
            if ($route == 'news/index' && isset($params['centerid'])) {
                $center = Center::findOne($params['centerid']);
                if ($center) {
                    $regionId = $center->region;
                }
            }

            if ($regionId)
            {
                // Берем алиас региона:
                $region_alias = Yii::$app->db
                                  ->createCommand('SELECT alias FROM region WHERE id=:rid',
                                      [':rid' => $regionId])
                                  ->queryScalar();
                if ($region_alias)
                {
                    // Алиас потом выставим как часть урла. Из параметров регион нужно убрать:
                    if (isset($params['region'])) {
                        unset($params['region']);
                    }

                    // Не подставляем в УРЛ пустые поля из формы:
            		foreach($params as $k => $v) {
            		    if (isset($params[$k]) && $params[$k] == '') {
            			    unset($params[$k]);
                        }
                    }
                    if (isset($params['is24x7']) && $params['is24x7'] == '0') {
                        unset($params['is24x7']);
                    }
        			foreach($params as $k => $v) {
        				if (isset($params[$k]) && $params[$k] == '') {
        					unset($params[$k]);
                        }
                    }

                    $station_slug = '';
                    if (isset($params['metro'])) {
                        $station = Station::findOne($params['metro']);
                        if ($station && $station->slug) {
                            $station_slug = $station->slug;
                        }
                        unset($params['metro']);
                    }

                    // Этот параметр может придти только для списка новостей ("новости коворкинга"):
                    $center_alias = '';
                    if (isset($params['centerid'])) {
                        $center = Center::findOne($params['centerid']);
                        if ($center && $center->alias) {
                            $center_alias = $center->alias;
                        }
                        unset($params['centerid']);
                    }

                    $url = parent::createUrl($params);
            		if($region_alias)
                    {
            			$url = str_replace('centers', $region_alias.'/centers', $url);
                        $url = str_replace('news', $region_alias.'/news', $url);
                        $url = str_replace('events', $region_alias.'/events', $url);
                        $url = str_replace('arenda', $region_alias.'/arenda', $url);
                    }

                    if ($station_slug) {
                        if ($route == 'center/map') {
                            $url = str_replace('/centers/map/', '/centers/map/'.$station_slug.'/', $url);
                        } else if ($route == 'arenda/map') {
                            $url = str_replace('/arenda/map/', '/arenda/map/'.$station_slug.'/', $url);
                        } else {
                            $url = str_replace('/centers/', '/centers/'.$station_slug.'/', $url);
                            $url = str_replace('/news/', '/news/'.$station_slug.'/', $url);
                            $url = str_replace('/events/', '/events/'.$station_slug.'/', $url);
                            $url = str_replace('/arenda/', '/arenda/'.$station_slug.'/', $url);
                        }
                    }

                    if ($center_alias) {
                        $url = str_replace('/news/', '/news/'.$center_alias.'/', $url);
                    }
                }
            }
        }

        return $url;
    }

    public function parseRequest($request)
    {
        $pathInfo = $request->pathInfo;
        $params = $request->queryParams;

    	// Страница авторизации:
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

        // Страница "О проекте":
    	if (preg_match('%^about/$%', $pathInfo, $matches))
    	{
    		return ['site/about', $params];
    	}

        // Страница "Контакты":
    	if (preg_match('%^contacts/$%', $pathInfo, $matches))
    	{
    		return ['site/contacts', $params];
    	}

        // Страница "Реклама":
    	if (preg_match('%^adv/$%', $pathInfo, $matches))
    	{
    		return ['site/adv', $params];
    	}

        // Страница "Метро":
    	if (preg_match('%^updatemetro/$%', $pathInfo, $matches))
    	{
    		return ['site/updatemetro', $params];
    	}

        // Страница "Регионы":
    	if (preg_match('%^regions/$%', $pathInfo, $matches))
    	{
    		return ['region/index', $params];
    	}

        // Страница "Администрирование":
    	if (preg_match('%^admin/$%', $pathInfo, $matches))
    	{
    		return ['site/admin', $params];
    	}

        //Главная страница с заданным регионом:
        if (preg_match('%^([a-z0-9-_]+)/$%', $pathInfo, $matches))
        {
            // Один карман: matches[1] - предполагаемый алиас региона

            // Сначала ищем совпадение $matches[1] с алиасом региона:
            $region_id = Yii::$app->db
                ->createCommand('SELECT id FROM region
                                  WHERE alias=:region' , [':region' => $matches[1]])
                ->queryScalar();

            //Если нашли - распознавание завершено:
            if ($region_id) {
                return ['site/index', ['region' => $region_id]];
            }
        }

        // Единообразная обработка тройки (сущность, список, карта) для: коворкингов, объявлений, новостей, мероприятий
        function doParse($pathInfo, $params, $matches, $itemName, $itemNameURL)
        {
            if (preg_match('%^([a-z0-9-_]+)/'.$itemNameURL.'/(\S*)$%', $pathInfo, $matches))
            {
                // Два кармана:
                // matches[1] - предполагаемый алиас региона,
                // matches[2] - "остальной" хвост.

                // Сначала ищем совпадение $matches[1] с алиасом региона:
                $region_id = Yii::$app->db
                    ->createCommand('SELECT id FROM region
                                      WHERE alias=:region' , [':region' => $matches[1]])
                    ->queryScalar();

                // Если нашли - можно о чем-то говорить дальше:
                if ($region_id)
                {
                    // Сначала варианты, когда это страница сущности:
                    if (preg_match('%([a-z0-9-_]+)/?$%', $matches[2], $detailed_matches))
                    {
                        // Это так, когда в кармане $detailed_matches[1]:

                        // - либо алиас сущности (для center и arenda):
                        if ($itemName == 'center' || $itemName == 'arenda') {
                            $item_id = Yii::$app->db
                                ->createCommand('SELECT c.id AS `cid`
                                                  FROM '.$itemName.' AS `c`, region AS `r`
                                                  WHERE c.region = r.id
                                                  AND c.alias=:itemAlias
                                                  AND r.alias=:region' , [':itemAlias' => $detailed_matches[1], ':region' => $matches[1]])
                                ->queryScalar();

                            if ($item_id)
                            {
                                $params['id'] = $item_id;
                                return [$itemName.'/view', $params];
                            }
                        }

                        // - либо id сущности (для news и events):
                        if ($itemName == 'news' || $itemName == 'event') {
                            $item_id = Yii::$app->db
                                ->createCommand('SELECT c.id AS `cid`
                                                  FROM '.$itemName.' AS `c`
                                                  WHERE c.id=:itemId',
                                                  [':itemId' => $detailed_matches[1]])
                                ->queryScalar();

                            if ($item_id)
                            {
                                $params['id'] = $item_id;
                                return [$itemName.'/view', $params];
                            }
                        }

                        // Если мы сюда дошли, уже ясно, что это не сущность.
                        // Но текущим регулярным разбором еще стоит попользоваться:
                        // В $detailed_matches[1] может сидеть:
                        // - станция метро;
                        // - точная локация;
                        // - алиас коворкинга (для списка новостей коворкинга);
                        $st = Station::findOne(['slug' => $detailed_matches[1], 'region' => $region_id]);
                        if ($st) {
                            $params['metro'] = $st->id;
                        }

                        $center = Center::findOne([ 'alias' => $detailed_matches[1] ]);
                        if ($center) {
                            $params['centerid'] = $center->id;
                        }

                    }

                    // Итак, это не сущность. Значит, это страница index или map или coordinates:

                    // Вручную установим в форме поиска регион:
                    if ($itemName == 'center' || $itemName == 'arenda' || $itemName == 'news') {
                        $params['region'] = $region_id;
                    }
                    // if ($itemName == 'center' || $itemName == 'arenda') {
                    //     $params[ucfirst($itemName).'Search']['region'] = $region_id;
                    // }

                    // Карта
                    if(preg_match('%^map/\S*$%', $matches[2])) {
                        //Yii::info($params, 'myd');
                        //Yii::info($itemName.'/map', 'myd');
                        return [$itemName.'/map', $params];
                    }

                    // Координаты
                    if(preg_match('%^coordinates/\S*$%', $matches[2])) {
                        return [$itemName.'/coordinates', $params];
                    }

                    // Список
                    //Yii::info($params, 'myd');
                    return [$itemName.'/index', $params];
                }
            }

            return null;
        }

        $res1 = doParse($pathInfo, $params, $matches, 'center', 'centers');
        if ($res1) {
            return $res1;
        }

        $res1 = doParse($pathInfo, $params, $matches, 'arenda', 'arenda');
        if ($res1) {
            return $res1;
        }

        $res1 = doParse($pathInfo, $params, $matches, 'news', 'news');
        if ($res1) {
            return $res1;
        }

        $res1 = doParse($pathInfo, $params, $matches, 'event', 'events');
        if ($res1) {
            return $res1;
        }


        // Единообразная обработка тройки (сущность, список, карта) для: коворкингов, объявлений, новостей, мероприятий (старая версия - с таких страниц делается 301 редирект на новые УРЛы):
        function doParseOld ($pathInfo, $matches, $itemName, $itemNameURL)
        {
            if (preg_match('%^'.$itemNameURL.'/([a-z0-9-_]+)/(\S*)$%', $pathInfo, $matches))
            {
                // Два кармана:
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
                        $item_id = Yii::$app->db
                            ->createCommand('SELECT c.id AS `cid`
                                              FROM '.$itemName.' AS `c`, region AS `r`
                                              WHERE c.region = r.id
                                              AND c.alias=:itemAlias
                                              AND r.alias=:region' , [':itemAlias' => $detailed_matches[1], ':region' => $matches[1]])
                            ->queryScalar();

                        // Если таки да, это страница сущности (старый УРЛ). Делаем редирект на правильный УРЛ
                        if ($item_id)
                        {
                            Yii::$app->response->redirect('/'.$matches[1].'/'.$itemNameURL.'/'.$detailed_matches[1].'/', 301);
                            return ['', []];
                        }
                    }

                    // Если же нет (мы сюда дошли), это страница index или map или coordinates

                    if(preg_match('%^map/\S*$%', $matches[2])) {
                        Yii::$app->response->redirect('/'.$matches[1].'/'.$itemNameURL.'/map/', 301);
                        return ['', []];
                    }

                    if(preg_match('%^coordinates/\S*$%', $matches[2])) {
                        Yii::$app->response->redirect('/'.$matches[1].'/'.$itemNameURL.'/coordinates/', 301);
                        return ['', []];
                    }

                    Yii::$app->response->redirect('/'.$matches[1].'/'.$itemNameURL.'/', 301);
                    return ['', []];
                }
            }
        }

        $res1 = doParseOld($pathInfo, $matches, 'center', 'centers');
        if ($res1) {
            return $res1;
        }

        $res1 = doParseOld($pathInfo, $matches, 'arenda', 'arenda');
        if ($res1) {
            return $res1;
        }


        //Если "наш" код не сработал (и мы сюда таки дошли) - ладно, просто вызовем родительский метод:
        return parent::parseRequest($request);
    }
}
