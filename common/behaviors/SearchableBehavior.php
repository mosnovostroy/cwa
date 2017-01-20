<?php
namespace common\behaviors;

use yii\behaviors\AttributeBehavior;
use yii\base\InvalidCallException;
use yii\db\BaseActiveRecord;
use Yii;

class SearchableBehavior extends AttributeBehavior
{
    /**
     * @var array
     *
     */
    public $dataForFastSearch = [];

    public $fastsearchAttribute = 'fastsearch';

    public $value;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => $this->fastsearchAttribute,
                BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->fastsearchAttribute,
            ];
        }
    }

    /**
     * @inheritdoc
     *
     * In case, when the [[value]] is `null`, the .................
     * will be used as value.
     */
    protected function getValue($event)
    {
        //Yii::info('getter',"myd");
        if ($this->value === null) {
            $arr = [];
            foreach ($this->dataForFastSearch as $field) {
                if (mb_strlen($this->owner->{$field}) > 1){
                    $arr[] = $this->owner->{$field};
                }
            }
            $arr = array_unique($arr);

            //$str = join(' ', $arr);
            $str = $this->fastsearch_format(join(' ', $arr));

            //Yii::info($str,'myd');
            return $str;
        }
        return parent::getValue($event);
    }

    /**
     * @inheritdoc
     *
     * !!!!!!!!!!!!!!! Copyright dap
     * Original: PWE2/class/main.class.php
     */
    protected function fastsearch_format($str){
        $str = html_entity_decode(trim($str), ENT_COMPAT, 'UTF-8');
        $str = trim(preg_replace(array("/([^a-z0-9а-яё_\- ]+)/iu", "/(\s+)/u"), array("", " "), $str));
        $arr = explode(" ", $str);
        foreach ((array)$arr as $key => $val){
            switch ($val){
                case "к.":
                case "к":
                case "корп.":
                case "корп":
                    $arr[$key] = "корпус";
                    break;
                case "д.":
                case "д":
                    $arr[$key] = "дом";
                    break;
                case "ул.":
                case "ул":
                case "у":
                    $arr[$key] = "улица";
                    break;
                case "вл.":
                case "вл":
                    $arr[$key] = "владение";
                    break;
                case "р-н":
                    $arr[$key] = "район";
                    break;
                case "уч.":
                case "уч":
                    $arr[$key] = "участок";
                    break;
                case "пр-т":
                case "просп":
                case "пр":
                case "пр.":
                    $arr[$key] = "проспект";
                    break;
                case "пос.":
                case "пос":
                    $arr[$key] = "поселок";
                    break;
                case "мкр.":
                case "мкр":
                    $arr[$key] = "микрорайон";
                    break;
                case "ш.":
                case "ш":
                    $arr[$key] = "шоссе";
                    break;
                case "пер":
                    $arr[$key] = "переулок";
                    break;
                case "дер":
                    $arr[$key] = "деревня";
                    break;
                case "г.":
                case "г":
                    $arr[$key] = "город";
                    break;
                case "б":
                case "б.":
                case "бул":
                case "бул.":
                    $arr[$key] = "бульвар";
                    break;
            }
        }
        $arr = array_unique((array)$arr);
        return join(" ", (array)$arr);
    }

}
