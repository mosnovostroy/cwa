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
	public $anons_text;
    public $anons_text_short;

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
            [['region', 'is_lead'], 'integer'],
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
            'title' => 'Заголовок',
            'text' => 'Текст',
			//	'content' => 'Текст',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'region' => 'Регион',
			'is_lead' => 'Тема дня',
        ];
    }

	public function beforeSave($insert)
	{
	    if (parent::beforeSave($insert)) {
			// Метатеги генерируются по вводимым полям автоматом:
			$this->meta_title = $this->title . ' :: Новости рынка :: Коворкинг-ревю';
			$this->meta_description = $this->title . ' :: Новости рынка :: Коворкинг-ревю';
			$this->meta_keywords = 'коворкинг, новости';
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

        $len = 500;
        $string = $this->text;
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

        $len = 70;
        $string = $this->text;
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
        $this->anons_text_short = $string;

        parent::afterFind();
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

		Yii::$app->db->createCommand('DELETE FROM Comment WHERE entity = :entity AND entityId = :entityId', [':entity' => $entity, ':entityId' => $entityId])
			->execute();

		return true;
	}
}
