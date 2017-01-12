<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "region".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent
 */
class Item extends \yii\base\Model
{
    public $text;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text'], 'string'],
        ];
    }

}
