<?php
namespace common\models;

use yii\base\Model;
use yii\base\InvalidParamException;
use common\models\User;
use common\models\Center;
use common\models\Location;
use Yii;
use yii\helpers\Json;
use yii\db\Query;
use yii\helpers\Url;

/**
 * Password reset form
 */
class Fastsearch extends Model
{
    public function searchCenters($q)
    {
        if (Yii::$app->regionManager->id) {
            $regionId = Yii::$app->regionManager->id;
        } else {
            $regionId = 1; // Москва до умолчанию
        }

        $arr = $this->getWords($q);

        $items = Center::find()->where(['region' => $regionId, 'status' => Center::STATUS_ACTUAL]);

        if (is_array($arr)){
            foreach ($arr as $key => $val){
                if (strlen($val) < 2){
                    continue;
                }
                if (preg_match("/^-(.*)/ui", $val, $reg)){
                    $items->andFilterWhere(['not like', 'fastsearch', $tmp]);
                } else {
                    if ( $tmp = preg_replace("/[^_\-а-яёА-ЯЁ\w\d\.,]/ui", "", $val) ){
                        $items->andFilterWhere(['like', 'fastsearch', $tmp]);
                    }
                }
                unset($reg, $tmp);
            }
        }

        $out = [];
        foreach($items->all() as $item) {
            // $output = $item->name;
            // $location = implode(', ', [$item->regionName, $item->address]);
            // if ($location) {
            //     $output .= ' ('.$location.')';
            // }
            $output = $item->name;
            $fullAddress = $item->fullAddress;
            if ($fullAddress) {
                //$output .= ' | '.$fullAddress;
                $output .= ' ('.$fullAddress.')';
            }
            $out[] = [
                'value' => $output,
                'url' => Url::to(['center/view', 'id' => $item->id]),
            ];
        }

        return Json::encode($out);
    }


    public function searchStations($q)
    {
        if (Yii::$app->regionManager->id) {
            $regionId = Yii::$app->regionManager->id;
        } else {
            $regionId = 1; // Москва до умолчанию
        }

        $arr = $this->getWords($q);

        $items = Station::find()->where(['region' => $regionId]);

        if (is_array($arr)){
            foreach ($arr as $key => $val){
                if (strlen($val) < 2){
                    continue;
                }
                if (preg_match("/^-(.*)/ui", $val, $reg)){
                    $items->andFilterWhere(['not like', 'fastsearch', $tmp]);
                } else {
                    if ( $tmp = preg_replace("/[^_\-а-яёА-ЯЁ\w\d\.,]/ui", "", $val) ){
                        $items->andFilterWhere(['like', 'fastsearch', $tmp]);
                    }
                }
                unset($reg, $tmp);
            }
        }

        $out = [];
        foreach($items->all() as $item) {
            //$output = 'Метро '.$item->name.' ('.$item->regionName.')';
            //$output = $item->name.' ('.$item->regionName.')';
            $output = $item->name;
            $out[] = [
                'value' => $output,
                'url' => Url::to(['center/index', 'region' => $item->regionId, 'metro' => $item->id]),
            ];
        }

        return Json::encode($out);
    }

    public function searchLocations($q)
    {
        if (Yii::$app->regionManager->id) {
            $regionId = Yii::$app->regionManager->id;
        } else {
            $regionId = 1; // Москва до умолчанию
        }

        $arr = $this->getWords($q);

        $items = Location::find()->where(['region' => $regionId]);

        if (is_array($arr)){
            foreach ($arr as $key => $val){
                if (strlen($val) < 2){
                    continue;
                }
                if (preg_match("/^-(.*)/ui", $val, $reg)){
                    $items->andFilterWhere(['not like', 'fastsearch', $tmp]);
                } else {
                    if ( $tmp = preg_replace("/[^_\-а-яёА-ЯЁ\w\d\.,]/ui", "", $val) ){
                        $items->andFilterWhere(['like', 'fastsearch', $tmp]);
                    }
                }
                unset($reg, $tmp);
            }
        }

        $out = [];
        foreach($items->all() as $item) {
            //$output = 'Метро '.$item->name.' ('.$item->regionName.')';
            //$output = $item->name.' ('.$item->regionName.')';
            $output = $item->name;
            $out[] = [
                'value' => $output,
                'url' => Url::to(['center/index', 'region' => $item->regionId, 'location' => $item->id]),
            ];
        }

        return Json::encode($out);
    }


    protected function getWords ($q)
    {
        $q = substr(trim($q), 0, 100); // укоротить до 100 симв.

        $a1 = array(
            "/[^а-яёА-ЯЁa-zA-Z\w\d\.,_\- \+]+/ui", // убрать все лишние символы
            "/\+\s([^\s]+)/u", // удалить пробел между плюсом и словом
            "/-\s([^\s]+)/u", // удалить пробел между минусом и словом
            "/ +/u", // объединить идущие подряд пробелы в один
        );
        $a2 = array(
            "",
            "+$1",
            "-$1",
            " "
        );
        $str = trim(preg_replace($a1, $a2, $q));
        $arr = (array) explode(" ", $str); // разбиваем по пробелам
        $arr = array_unique($arr); // убираем повторяющиеся слова

        return $arr;

    }

}
