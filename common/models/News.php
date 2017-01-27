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


/**
 * This is the model class for table "news".
 *

 */
class News extends \yii\db\ActiveRecord
{
	public $date;
	public $eventAtFormatted;
	public $anons_text;
    public $anons_text_short;

	// Убрали это поле из таблицы news. Но чтобы работало поведение RegionInfoBehavior, добавляем здесь
	// Понадобится, например, при отображении списка новостей данного региона через NewsSearch
	public $region = 1;

	public function fields()
    {
        $fields = parent::fields();

        return $fields;
    }

	 /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['title', 'text'], 'required'],
			[['title'], 'string', 'max' => 255],
            [['text', 'meta_title', 'meta_description', 'meta_keywords'], 'string'],
            [['is_lead'], 'integer'],
			[['eventDate'], 'date', 'format' => 'php:d.m.Y'],
        ];
    }

	// В базе поле eventAt - типа int(11), то есть timestamp
	// Виджет Картика DatePicker не умеет (?) работать с таким.
	// Поэтому делаем геттер и сеттер для "поля" eventDate формата d.m.Y
	// И в форме уже работаем именно с этим полем (и в rules() тоже, соответственно, оно)
	// Идея взята здесь: https://toster.ru/q/209224
	public function getEventDate() {
        return $this->eventAt ? date('d.m.Y', $this->eventAt) : '';
    }

    public function setEventDate($date) {
        $this->eventAt = $date ? strtotime($date) : null;
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
            'title' => 'Заголовок',
            'text' => 'Текст',
			//	'content' => 'Текст',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
			'is_lead' => 'Тема дня',
			'eventDate' => 'Дата мероприятия',
        ];
    }

	public function beforeSave($insert)
	{
	    if (parent::beforeSave($insert)) {

			// meta: title
			$this->meta_title = $this->title . ' | Коворкинг-ревю';

			// meta: description
	        $this->meta_description = $this->generateAnons($this->text, 300);

			// meta: keywords
			$this->meta_keywords = 'коворкинг, новости';

			// og-title
	        $this->meta_og_title = $this->title;

			return true;
	    } else {
			return false;
		}
	}


	public function afterSave ($insert, $changedAttributes)
	{
		parent::afterSave ($insert, $changedAttributes);
	}

	public function afterFind()
    {
        Yii::$app->formatter->locale = 'ru-RU';
        $this->date = Yii::$app->formatter->asDate($this->createdAt, 'long');
		$this->eventAtFormatted = Yii::$app->formatter->asDate($this->eventAt, 'd MMMM, EEEE');

        $this->anons_text = $this->generateAnons($this->text, 500);;

		$this->anons_text_short = $this->generateAnons($this->text, 70);

        parent::afterFind();
    }

	public function generateAnons($string, $len)
	{
        $string = strip_tags($string);
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
        return $string;
	}

	public function getCenters()
    {
        return $this->hasMany(Center::className(), ['id' => 'center_id'])
             ->viaTable('news_center', ['news_id' => 'id']);
    }

	public function getRegions()
    {
        return $this->hasMany(Region::className(), ['id' => 'region_id'])
             ->viaTable('news_region', ['news_id' => 'id']);
    }

	//////////////////////////////////////////////////////////////////////////////////////////
 	// Deletes all comments from model:
	public function deleteAllComments()
	{
		$entity = 'cdaec8da';
		$entityId = $this->id;
		if (!$entity || !$entityId)
			return false;

		Yii::$app->db->createCommand('DELETE FROM comment WHERE entity = :entity AND entityId = :entityId', [':entity' => $entity, ':entityId' => $entityId])
			->execute();

		return true;
	}

	public function getCenterLogo ()
	{
		$centers = $this->centers;
		if ($centers) {
			foreach ($centers as $center) {
				$logo = $center->logoImage;
				if ($logo) {
					return $logo;
				}
			}
		}
		return '';
	}

	public function getCenter ()
	{
		$centers = $this->centers;
		if ($centers) {
			foreach ($centers as $center) {
				return $center;
				break;
			}
		}
		return null;
	}

}
