<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\FileHelper;
use common\behaviors\ImageBehavior;
use common\behaviors\RegionInfoBehavior;


/**
 * This is the model class for table "center".
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
class Center extends \yii\db\ActiveRecord
{
	 /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'center';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'alias'], 'required'],
            [['description', 'meta_title', 'meta_description', 'meta_keywords', 'features', 'tariffs'], 'string'],
            [['gmap_lat', 'gmap_lng', 'region', 'price_day', 'rating'], 'number'],
            [['region', 'price_hour', 'price_day', 'price_week', 'price_month', 'is24x7', 'has_fixed', 'has_storage', 'has_meeting_room', 'has_printer', 'has_internet', 'rating'], 'integer'],
            [['name', 'alias'], 'string', 'max' => 255],
						[['address', 'phone'], 'string'],
						['email', 'email'],
						['site', 'url', 'defaultScheme' => 'http'],
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
            'alias' => 'Алиас',
            'description' => 'Описание',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'gmap_lat' => 'Широта',
            'gmap_lng' => 'Долгота',
            'region' => 'Регион',
            'price_day' => 'цена',
            'rating' => 'рейтинг',
						'address' => 'Адрес',
						'phone' => 'Телефон',
						'email' => 'Email',
						'site' => 'Сайт',
        ];
    }


		public function updateFeaturesFromArray($data)
		{
				if (!$this->_featuresModel)
						$this->_featuresModel = new Tariff;

				$this->_featuresModel->isTariff = 0;

				if ($this->_featuresModel->load($data))
				{
						$this->features = serialize($data);
						$this->resetCache();
						return $this->save();
				}
		}

		/////////////////////////////////////////////////////////////////////////////////////////////////
		// Параметр - тариф-модель (класс Tariff)
		public function addTariff($tariffModel)
		{
				// Заполняем временный локальный массив тарифов-массивов данными из поля бд:
				$tariffArrays = array();
				if ($this->tariffs)
						$tariffArrays = unserialize($this->tariffs);

				// Добавляем еще один тариф-массив
				$tariffArrays[] = $tariffModel->toArray();

				// Очищаем кэш класса и сохраняем изменения обратно в поле бд
				$this->resetCache();
				$this->tariffs = serialize($tariffArrays);
				return $this->save();
		}

		/////////////////////////////////////////////////////////////////////////////////////////////////
		// Параметр - id тарифа-массива (ключ в массиве массивов)
		public function deleteTariff($id)
		{
				// Заполняем временный локальный массив тарифов-массивов данными из поля бд:
				$tariffArrays = array();
				if ($this->tariffs)
						$tariffArrays = unserialize($this->tariffs);

				// Удаляем из него один тариф-массив
				unset($tariffArrays[$id]);

				// Очищаем кэш класса и сохраняем изменения обратно в поле бд
				$this->resetCache();
				$this->tariffs = serialize($tariffArrays);
				return $this->save();
		}

		/////////////////////////////////////////////////////////////////////////////////////////////////
		// Параметр - id тарифа-массива (ключ в массиве массивов)
		public function updateTariff($id, $tariffModel)
		{
				// Заполняем временный локальный массив тарифов-массивов данными из поля бд:
				$tariffArrays = array();
				if ($this->tariffs)
						$tariffArrays = unserialize($this->tariffs);

				// Заменяем один тариф-массив
				$tariffArrays[$id] = $tariffModel->toArray();

				// Очищаем кэш класса и сохраняем изменения обратно в поле бд
				$this->resetCache();
				$this->tariffs = serialize($tariffArrays);
				return $this->save();
		}

		/////////////////////////////////////////////////////////////////////////////////////////////////
		// Параметр - id тарифа-массива. Возвращает тариф-модель
		public function getTariffModel($id)
		{
				// Заполняем временный локальный массив тарифов-массивов данными из поля бд:
				$tariffArrays = array();
				if ($this->tariffs)
						$tariffArrays = unserialize($this->tariffs);

				// Готовим тариф-модель
				$tariff = new Tariff;

				// Выбираем нужный тариф-массив и заполняем тариф-модель
				$tariff->load($tariffArrays[$id]);

				return $tariff;
		}

		// Кэшируются модели, которые вычисляются по полям center.features и center.tariffs
		// и используются через геттеры.
		// При любых изменениях модели кэш нужно сбрасывать вызовом этого метода
		public function resetCache()
		{
				$this->_featuresModel = null;
				$this->_tariffModels = null;
		}

		// Модель (класс Tariff) "общих параметров центра" доступна через свойство featuresModel,
		// определяемое через геттер
		// Кэшируем в private свойстве
		private $_featuresModel;
		public function getFeaturesModel()
		{
				if (!$this->_featuresModel)
				{
						$this->_featuresModel = new Tariff;
						$this->_featuresModel->isTariff = 0;
						$this->_featuresModel->loadFromArray(unserialize($this->features));
				}
				return $this->_featuresModel;
		}

		// Массив моделей (классы Tariff) менованных тарифов доступен через свойство tariffModels,
		// определяемое через геттер
		// Кэшируем этот массив в private свойстве
		private $_tariffModels;
		public function getTariffModels()
		{
				if (!$this->_tariffModels)
				{
						$arr = array();
						$tariffArrays = unserialize($this->tariffs);
						if ($tariffArrays)
								foreach($tariffArrays as $k => $tariffArray)
								{
										$tariffModel = new Tariff;
										$tariffModel->loadFromArray($tariffArray);
										$tariffModel->id = $k;
										$arr[$k] = $tariffModel;
								}
						$this->_tariffModels = $arr;
				}
				return $this->_tariffModels;
		}

		public function afterFind()
    {
				$this->getFeaturesModel();
				$this->getTariffModels();
				parent::afterFind();
    }

}
