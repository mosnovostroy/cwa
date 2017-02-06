<?php
namespace frontend\models;

use yii\base\Model;
use yii\base\InvalidParamException;
use common\models\User;
use common\models\Center;
use common\models\Location;
use Yii;

/**
 * Password reset form
 */
class UpdateFastsearch extends Model
{
    public function doUpdate()
    {
        $centers = Center::find()->all();
        foreach ($centers as $item) {

            // Искусственно сбросим поле fastsearch.
            // Без этого не вызывается событие EVENT_BEFORE_UPDATE и не запускается обработчик поведения
            $item->fastsearch = '1';

            $item->save();
        }

        $items = Location::find()->all();
        foreach ($items as $item) {

            // Искусственно сбросим поле fastsearch.
            // Без этого не вызывается событие EVENT_BEFORE_UPDATE и не запускается обработчик поведения
            $item->fastsearch = '1';

            $item->save();
        }

        return true;
    }
}
