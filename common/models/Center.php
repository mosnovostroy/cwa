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
						[['alias'], 'match', 'pattern' => '/^[a-zA-Z0-9-]+/i'],
            [['description', 'meta_title', 'meta_description', 'meta_keywords', 'features', 'tariffs'], 'string'],
            [['gmap_lat', 'gmap_lng', 'region', 'price_day', 'rating'], 'number'],
            [['region', 'price_hour', 'price_day', 'price_week', 'price_month', 'is24x7', 'has_fixed', 'has_storage', 'has_meeting_room', 'has_printer', 'has_internet', 'rating'], 'integer'],
            [['name', 'alias'], 'string', 'max' => 255],
						[['address', 'phone'], 'string'],
						['email', 'email'],
						['site', 'url', 'defaultScheme' => 'http', 'enableIDN' => true],
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
            'region' => 'Расположение объекта',
            'price_month' => 'цена',
						'address' => 'Адрес',
						'phone' => 'Телефон',
						'email' => 'Email',
						'site' => 'Сайт',
						'is24x7' => 'Круглосуточно',
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

		/////////////////////////////////////////////////////////////////////////////////////////////
    //Рендеринг списка для карточки в результатах поиска
		private $_paramsList;
		public function getParamsList()
		{
			if (!$this->_paramsList)
			{
					$arr = array();

					if ($this->price_hour) $arr[] = 'от '.$this->price_hour.' руб. в час';
					if ($this->price_day) $arr[] = 'от '.$this->price_day.' руб. в день';
					if ($this->price_week) $arr[] = 'от '.$this->price_week.' руб. в неделю';
					if ($this->price_month) $arr[] = 'от '.$this->price_month.' руб. в месяц';

					$this->_paramsList = $arr;
			}
			return $this->_paramsList;
		}

		// Вычисляем цены-поля на базе цен в полях features и tariffs
		public function updatePrices()
		{
				$features = $this->getFeaturesModel();
				$tariffs = $this->getTariffModels();

				$this->price_hour = 0;
				if ($features->price_hour) $this->price_hour = $features->price_hour;
				foreach ($tariffs as $tariff)
						if ($tariff->price_hour && (!$this->price_hour || $tariff->price_hour < $this->price_hour))
								$this->price_hour = $tariff->price_hour;

				$this->price_day = 0;
				if ($features->price_day) $this->price_day = $features->price_day;
				foreach ($tariffs as $tariff)
						if ($tariff->price_day && (!$this->price_day || $tariff->price_day < $this->price_day))
								$this->price_day = $tariff->price_day;

				$this->price_week = 0;
				if ($features->price_week) $this->price_week = $features->price_week;
				foreach ($tariffs as $tariff)
						if ($tariff->price_week && (!$this->price_week || $tariff->price_week < $this->price_week))
								$this->price_week = $tariff->price_week;

				$this->price_month = 0;
				if ($features->price_month) $this->price_month = $features->price_month;
				foreach ($tariffs as $tariff)
						if ($tariff->price_month && (!$this->price_month || $tariff->price_month < $this->price_month))
								$this->price_month = $tariff->price_month;
		}

		// Работает ли центр круглосуточно
		public function is24x7()
		{
				$features = $this->getFeaturesModel();
				$tariffs = $this->getTariffModels();
				if ($features->is24x7()) return true;
				foreach ($tariffs as $tariff)	if ($tariff->is24x7()) return true;
				return false;
		}

		// Можно ли зафиксировать свое рабочее место
		public function hasFixed()
		{
				$features = $this->getFeaturesModel();
				$tariffs = $this->getTariffModels();
				if ($features->hasFixed()) return true;
				foreach ($tariffs as $tariff)	if ($tariff->hasFixed()) return true;
				return false;
		}

		// Предоставляется ли место для хранения личных вещей
		public function hasStorage()
		{
				$features = $this->getFeaturesModel();
				$tariffs = $this->getTariffModels();
				if ($features->hasStorage()) return true;
				foreach ($tariffs as $tariff)	if ($tariff->hasStorage()) return true;
				return false;
		}

		// Предоставляется ли доступ к принтеру
		public function hasPrinter()
		{
				$features = $this->getFeaturesModel();
				$tariffs = $this->getTariffModels();
				if ($features->hasPrinter()) return true;
				foreach ($tariffs as $tariff)	if ($tariff->hasPrinter()) return true;
				return false;
		}

		// Есть ли переговорная комната
		public function hasMeetingRoom()
		{
				$features = $this->getFeaturesModel();
				$tariffs = $this->getTariffModels();
				if ($features->hasMeetingRoom()) return true;
				foreach ($tariffs as $tariff)	if ($tariff->hasMeetingRoom()) return true;
				return false;
		}

		public function beforeSave($insert)
		{
		    if (parent::beforeSave($insert))
				{
						// Вычисляем цены-поля на базе цен в полях features и tariffs
						$this->updatePrices();

						// Работает ли центр круглосуточно
						$this->is24x7 = $this->is24x7();

						// Можно ли зафиксировать свое рабочее место
						$this->has_fixed = $this->hasFixed();

						// Предоставляется ли место для хранения личных вещей
						$this->has_storage = $this->hasStorage();

						// Предоставляется ли доступ к принтеру
						$this->has_printer = $this->hasPrinter();

						// Есть ли переговорная комната
						$this->has_meeting_room = $this->hasMeetingRoom();

						// Есть ли интернет :)

		        return true;
		    }
				else
		        return false;
		}

}
