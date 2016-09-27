<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\FileHelper;
use common\behaviors\ImageBehavior;
use common\behaviors\RegionInfoBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use common\models\User;

/**
 * This is the model class for table "arenda".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $gmap_lat
 * @property string $gmap_lng
 * @property integer $region
 */
class Arenda extends \yii\db\ActiveRecord
{
    public $username;
    public $date;
    public $anons_text;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'arenda';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string', 'max' => 1500],
            [['gmap_lat', 'gmap_lng', 'region', 'rating'], 'number'],
            [['name', 'alias', 'contacts'], 'string', 'max' => 150],
			      [['createdBy', 'updatedBy', 'createdAt', 'updatedAt'], 'integer'],
        ];
    }

    /**
     * Returns a list of behaviors that this component should behave as.
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'image' => [
                'class' => ImageBehavior::className(),
            ],
            'regionInfo' => [
                'class' => RegionInfoBehavior::className(),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'createdBy',
                'updatedByAttribute' => 'updatedBy',
            ],
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Заголовок объявления',
            'alias' => 'Алиас',
            'description' => 'Текст объявления',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'gmap_lat' => 'Широта',
            'gmap_lng' => 'Долгота',
            'region' => 'Расположение объекта',
            'contacts' => 'Контакты',
            'rating' => 'рейтинг',
            'createdAt' => 'дата',
        ];
    }

    public function afterFind()
    {
        $user = User::findOne($this->createdBy);
        if ($user)
            $this->username = $user->username;

        Yii::$app->formatter->locale = 'ru-RU';
        $this->date = Yii::$app->formatter->asDate($this->createdAt, 'long');

        $len = 500;
        $string = $this->description;
        if (mb_strlen($string) > $len)
        {
            $string = mb_substr($string, 0, $len);
            $limit = mb_strrpos($string, ' ');
            if ($limit)
            {
                $string = mb_substr($string, 0, $limit);
            }
            $string .= '...';
        }
        $this->anons_text = $string;

        parent::afterFind();
    }

    public function createAlias()
    {
        $this->alias = 'id10'.$this->id;
        $this->save();
     }

}
