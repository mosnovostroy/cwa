<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\behaviors\RegionInfoBehavior;

/**
 * This is the model class for table "region".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent
 */
class Location extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'location';
    }

    public function behaviors()
    {
        return [
            'regionInfo' => [
                'class' => RegionInfoBehavior::className(),
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'name_tp', 'alias', 'address_atom'], 'string', 'max' => 255],
            [['region'], 'integer'],
            [['name', 'name_tp', 'alias', 'region'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'name_tp' => 'Название в творительном падеже ("Коворкинги в ... ")',
            'alias' => 'Алиас',
            'region' => 'Регион',
            'address_atom' => 'Наименование региона в адресе'
        ];
    }
}
