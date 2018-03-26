<?php
namespace frontend;

use yii\web\UrlManager;
use Yii;
use yii\helpers\Url;

use common\models\Article;
use common\models\ArticleCategory;
use common\models\Realty;
use common\models\Realtor;
use common\models\Region;

class MosnovostroyUrlManager extends UrlManager
{
    public function parseRequest($request)
    {
        $pathInfo = $request->pathInfo;
        $params = $request->queryParams;

        // Массив элементов урла между слешами
        // Например, для /news/ad/33900.html элементы массива $pathItems такие ['news', 'ad', '33900.html']
        $pathItems = explode('/', trim($pathInfo, '/'));

        // Количество элементов
        $itemsCount = count($pathItems);

        // Первый (нулевой) элемент назовем для краткости "модулем"
        $module = (isset($pathItems[0]) ? $pathItems[0] : "");


        if ($module == 'news') {

            // Просто /news/
            if ($itemsCount == 1) {
                return ['article/index', []];
            }

            // // Сначала ищем совпадение с алиасом региона, компании или id объекта:
            // if ($itemsCount == 2) {
            //     if (($realtor = Realtor::findOne(['alias' => $pathItems[1]])) !== null) {
            //         return ['article/index', ['searchPath' => $realtor->alias]];
            //     }
            //     if (($region = Region::findOne(['alias' => $pathItems[1]])) !== null) {
            //         return ['article/index', ['searchPath' => $region->alias]];
            //     }
            //     if (($realty = Realty::findOne(['id' => $pathItems[1]])) !== null) {
            //         return ['article/index', ['searchPath' => $realty->id]];
            //     }
            // }
            //
            // // Потом ищем совпадение с алиасом категории:
            // $category = ArticleCategory::find()->where(['slug' => $pathItems[1]])->one();
            // if (!$category) {
            //     Yii::$app->response->redirect('/news/', 301);
            //     return ['', []];
            // }
            //
            // // Если есть еще что-то кроме категории, это может быть материал
            // if (isset($pathItems[2])) {
            //
            //     // Это материал, когда в кармане $matches[1] id материала:
            //     if (preg_match('%^([0-9-_]+)\.html?$%', $pathItems[2], $matches)
            //     && ($article = Article::findOne($matches[1])) !== null) {
            //         return ['article/view', ['id' => $article->id]];
            //     }
            //
            //     // Если сюда дошли, считаем, что УРЛ распознать не удалось
            //     Yii::$app->response->redirect('/news/' .($category ? $category->slug . "/" : ""), 301);
            //     return ['', []];
            // } else {
            //     // Иначе это УРЛ категории - вида /news/lenta/
            //     return ['article/index', ['searchPath' => $category->slug ]];
            // }
        } else if ($module == 'foto') {
            // Просто /foto/
            if ($itemsCount == 1) {
                return ['article/index', []];
            }

            // Сначала ищем совпадение с id альбома:
            $album = Article::find()->where(['id' => $pathItems[1]])->one();
            if (!$album) {
                Yii::$app->response->redirect('/news/', 301);
                return ['', []];
            }

            return ['article/view', ['id' => $album->id ]];
        } elseif ($module == ''){
            return ['site/index', []];
        }

        // Редирект с articles на news
        if ($module == 'articles') {
            Yii::$app->response->redirect('/' . (str_ireplace('articles/', 'news/', $pathInfo)), 301);
            return ['', []];
        }

        // Редирект с /realty/himki/pik/map/ на /map/himki/pik/
        if ( stripos($pathInfo, "realty/") === 0 && stripos($pathInfo, "/map/") > 0 ) {
            $pathInfoNew = str_ireplace("/map/", "/", $pathInfo);
            $pathInfoNew = str_ireplace("realty/", "/map/", $pathInfoNew);
            Yii::$app->response->redirect($pathInfoNew, 301);
            return ['', []];
        }

        // Редирект с realtors/person на person и с realtors/jur на jur
        if ($module == 'realtors') {
            if (isset($pathItems[1]) && ($pathItems[1] == 'person' || $pathItems[1] == 'jur')) {
                Yii::$app->response->redirect('/' . (str_ireplace('realtors/', '', $pathInfo)), 301);
                return ['', []];
            } else {
                Yii::$app->response->redirect('/' . (str_ireplace('realtors/', 'realtor/', $pathInfo)), 301);
                return ['', []];
            }
        }

        // Редирект с banks на bank
        if ($module == 'banks') {
            Yii::$app->response->redirect('/' . (str_ireplace('banks/', 'bank/', $pathInfo)), 301);
            return ['', []];
        }

        // Если "наш" код не сработал (и мы сюда таки дошли) - ладно, просто вызовем родительский метод:
        return parent::parseRequest($request);
    }

    public function createUrl($params)
    {
        $route = trim($params[0], '/');
        $routeItems = explode('/', $route);
        $controller = $routeItems[0];
        $action = $routeItems[1];

        // В целом все работает через правила в _urlmanager. Но некоторые сгенерированные вызовом родительского метода creatUrl УРЛы нужно подкрутить.
        $url = parent::createUrl($params);

        // Новости - добавим категорию
        if ($controller == 'article' && $action == 'view' && isset($params['id'])) {
            if ( ($article = Article::findOne($params['id'])) !== null )  {
                $url = str_replace('news/', 'news/' . $article->category->slug . '/', $url);
            }
        }

        // Объекты
        if ($controller == 'realty') {

            // Костыль. Убрать статус, если он равен 0 ("по умолчанию - строится или недавно сдан")
            if (isset($params['status']) && $params['status'] == 0) {
                unset($params['status']);
                $url = parent::createUrl($params);
            }

            // Костыль. Убрать pricetype, если он равен 0 ("по умолчанию - 'за все'")
            if (isset($params['pricetype']) && $params['pricetype'] == 0) {
                unset($params['pricetype']);
                $url = parent::createUrl($params);
            }

            // Костыль. Пришлось изменить поведение стандартного метода createUrl, который вместо параметра-строки himki/pik использует строку с кодированным слешом, что делает невозможным работу правила в _urlManager
            $url = str_replace("%2F", "/", $url);
        }

        return $url;
    }
}
