<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\FileHelper;

class Tariff extends \yii\base\Model
{
    // id тарифа = ключ в массиве массивов (поле center.tariffs). Нумерация своя для каждого центра
    // Устанавливается автоматически. Используется в представлениях для формирования УРЛов админских кнопок
    public $id;

    // Флаг, указывающий, что это имнованный тариф (а не "общие параметры центра")
    public $isTariff = 1;

  	//Название тарифа (для именованных тарифов)
  	 public $name;

     //Описание тарифа (для именованных тарифов)
   	 public $descr;

  	//Цены
  	public $price_minute;
  	public $price_hour;
  	public $price_day;
  	public $price_week;
  	public $price_month;

  	//Закреплено ли рабочее место
  	public $is_fixed;

    // Параметры типа "есть ли" - хранение
    public $optionsStorage;

    // Параметры типа "есть ли" - питание
    public $optionsMeal;

    // Параметры типа "есть ли" - спорт и развлечения
    public $optionsRecr;

    // Параметры типа "есть ли" - технологии
    public $optionsTech;

    // Параметры типа "есть ли" - остальное
    public $optionsMisc;

    // Дополнительные параметры (указываются через запятую)
  	public $options;

  	//Принтер
  	public $printer_mode; // Режим доступа к принтеру
    public $printer_pages; // Сколько страниц можно напечатать за период


  	// Переговорная
  	public $meeting_room_mode; // Режим доступа к переговорной
  	public $meeting_room_hours; // Количество бесплатных часов за период

    // Курьерская доставка
  	public $courier_mode; // Режим предоставления курьера
  	public $courier_count; // Количество бесплатных доставок за период

    // Гостевые визиты
  	public $guest_mode; // Режим пропуска гостей
  	public $guest_count; // Количество бесплатных визитов за период

  	//Режим работы и часы работы
  	public $days_1_mode; // Работает ли (и как именно) в понедельник
  	public $days_2_mode; // Работает ли (и как именно) во вторник
  	public $days_3_mode; // Работает ли (и как именно) в среду
  	public $days_4_mode; // Работает ли (и как именно) в четверг
  	public $days_5_mode; // Работает ли (и как именно) в пятницу
  	public $days_6_mode; // Работает ли (и как именно) в субботу
  	public $days_7_mode; // Работает ли (и как именно) в воскресенье

  	public $days_1_open;
  	public $days_1_close;
  	public $days_2_open;
  	public $days_2_close;
  	public $days_3_open;
  	public $days_3_close;
  	public $days_4_open;
  	public $days_4_close;
  	public $days_5_open;
  	public $days_5_close;
  	public $days_6_open;
  	public $days_6_close;
  	public $days_7_open;
  	public $days_7_close;

    public function loadFromArray($data)
    {
        $this->resetCache();
        return $this->load($data);
    }

    // Кэшируются массивы, которые вычисляются и используются через геттеры при рендеринге (см. ниже)
    // При любых изменениях модели (в частности при load) кэш нужно сбрасывать вызовом этого метода
    public function resetCache()
    {
        $this->_prices = null;
        $this->_paramsList = null;
        $this->_optionsList = null;
        $this->_timetable = null;
        return true;
    }

    public function rules()
    {
        return [
			[['id'], 'integer'],
			[['name', 'descr', 'options'], 'string'],
      [['price_minute', 'price_hour', 'price_day', 'price_week', 'price_month'], 'integer'],
			[['is_fixed'], 'integer'],
			[['optionsStorage', 'optionsMeal', 'optionsRecr', 'optionsTech', 'optionsMisc'], 'each', 'rule' => ['integer']],
			[['printer_pages', 'printer_mode'], 'integer'],
			[['meeting_room_hours', 'meeting_room_mode'], 'integer'],
      [['courier_count', 'courier_mode'], 'integer'],
      [['guest_count', 'guest_mode'], 'integer'],
			[['days_1_open', 'days_1_close', 'days_2_open', 'days_2_close', 'days_3_open', 'days_3_close', 'days_4_open', 'days_4_close', 'days_5_open', 'days_5_close', 'days_6_open', 'days_6_close', 'days_7_open', 'days_7_close', ], 'integer'],
			[['days_1_mode', 'days_2_mode', 'days_3_mode', 'days_4_mode', 'days_5_mode', 'days_6_mode', 'days_7_mode'], 'integer'],
        ];
    }

  	public function formName()
  	{
  		return '';
  	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'name' => 'Название тарифа',
      'descr' => 'Описание тарифа',
			'is_fixed' => 'Рабочее место закрепляется',
      'optionsStorage' => 'Хранение',
      'optionsMeal' => 'Питание',
      'optionsRecr' => 'Спорт и развлечения',
      'optionsTech' => 'Технологии',
      'optionsMisc' => 'Прочее',
      'options' => 'Также предоставляется',
			'price_minute' => 'Цена за минуту',
			'price_hour' => 'Цена за час',
			'price_day' => 'Цена за день',
			'price_week' => 'Цена за неделю',
			'price_month' => 'Цена за месяц',
			'printer_mode' => 'Принтер',
			'meeting_room_mode' => 'Переговорная',
      'courier_mode' => 'Курьерская доставка',
      'guest_mode' => 'Гостевые визиты',
			'days_1_mode' => 'Понедельник',
			'days_2_mode' => 'Вторник',
			'days_3_mode' => 'Среда',
			'days_4_mode' => 'Четверг',
			'days_5_mode' => 'Пятница',
			'days_6_mode' => 'Суббота',
			'days_7_mode' => 'Воскресенье',
			];
    }

    /////////////////////////////////////////////////////////////////////////////////////////////
    //Рендеринг элементов блока: цены и фиксированность рабочего места
    public function getFixMap()
  	{
  		return [ 0 => 'Не указано', 1 => 'Да', 2 => 'Нет' ];
  	}

    private $_prices;
		public function getPrices()
		{
				if (!$this->_prices)
				{
						$arr = array();
						if ($this->price_minute) $arr[] = $this->price_minute.' руб/мин';
						if ($this->price_hour) $arr[] = $this->price_hour.' руб/час';
						if ($this->price_day) $arr[] = $this->price_day.' руб/день';
						if ($this->price_week) $arr[] = $this->price_week.' руб/нед';
						if ($this->price_month) $arr[] = $this->price_month.' руб/мес';
						switch ($this->is_fixed)
						{
						case 1:
								//$arr[] = '<strong>Фиксированное рабочее место</strong>'; break;
                $arr[] = 'Фиксированное рабочее место'; break;
						case 2:
								$arr[] = 'Рабочее место не фиксируется'; break;
						}
						$this->_prices = $arr;
				}
				return $this->_prices;
		}
		public function getIssetPrices()
		{
				$this->getPrices();
				return ( $this->_prices && count($this->_prices) > 0 ) ? true : false;
		}

    /////////////////////////////////////////////////////////////////////////////////////////////
    //Рендеринг элементов блока: принтер, переговорная, курьерская доставка, гостевые визиты
    public function getPrinterModeMap()
  	{
  		return [ 0 => 'Не указано', 1 => 'Не более N страниц в день', 2 => 'Нет', 3 => 'Не ограничено', 4 => 'Предоставляется', 5 => 'Не более N страниц в неделю', 6 => 'Не более N страниц в месяц' ];
  	}

    public function getMeetingRoomModeMap()
  	{
  		return [ 0 => 'Не указано', 1 => 'Не более N часов в день', 2 => 'Не более N часов в неделю', 3 => 'Не более N часов в месяц', 4 => 'Нет', 5 => 'Не ограничено', 6 => 'Предоставляется' ];
    }

    public function getCourierModeMap()
  	{
      return [ 0 => 'Не указано', 1 => 'Не более N раз в день', 2 => 'Не более N раз в неделю', 3 => 'Не более N раз в месяц', 4 => 'Нет', 5 => 'Не ограничено', 6 => 'Предоставляется' ];
  	}

    public function getGuestModeMap()
  	{
      return [ 0 => 'Не указано', 1 => 'Не более N раз в день', 2 => 'Не более N раз в неделю', 3 => 'Не более N раз в месяц', 4 => 'Нет', 5 => 'Не ограничено', 6 => 'Предоставляется', 7 => 'Не более N часов в день', 8 => 'Не более N часов в неделю', 9 => 'Не более N часов в месяц' ];
  	}

    private $_paramsList;
		public function getParamsList()
		{
			if (!$this->_paramsList)
			{
					$arr = array();

					if ($this->printer_mode)
					{
							$arr[] = 'Принтер: '.(str_replace('N', ($this->printer_pages ? $this->printer_pages : 'N'), $this->getPrinterModeMap()[$this->printer_mode]));
					}
					if ($this->meeting_room_mode)
					{
							$arr[] = 'Переговорная: '.(str_replace('N', ($this->meeting_room_hours ? $this->meeting_room_hours : 'N'), $this->getMeetingRoomModeMap()[$this->meeting_room_mode]));
					}
          if ($this->courier_mode)
					{
							$arr[] = 'Курьерская доставка: '.(str_replace('N', ($this->courier_count ? $this->courier_count : 'N'), $this->getCourierModeMap()[$this->courier_mode]));
					}
          if ($this->guest_mode)
					{
							$arr[] = 'Гостевые визиты: '.(str_replace('N', ($this->guest_count ? $this->guest_count : 'N'), $this->getGuestModeMap()[$this->guest_mode]));
					}
					$this->_paramsList = $arr;
			}
			return $this->_paramsList;
		}

    /////////////////////////////////////////////////////////////////////////////////////////////
    //Рендеринг элементов блока: опции-"галочки"
    // public function getOptionsMap()
  	// {
  	// 	return [ 1 => 'Сейф', 2 => 'Ящик', 3 => 'Шкаф', 4 => 'Шкафчик', 5 => 'Личный ящик', 6 => 'Ниша для бумаг', 7 => 'Интернет', 8 => 'Wi-fi', 9 => 'Кухня', 10 => 'Кофе-брейк', 11 => 'Парковка', 12 => 'Напитки', 13 => 'Личный шкафчик для вещей', 14 => 'Личная страница на сайте' ];
  	// }
    public function getOptionsStorageMap()
  	{
  		return [ 1 => 'Сейф', 2 => 'Ящик', 3 => 'Шкаф', 4 => 'Шкафчик', 5 => 'Личный ящик', 6 => 'Ниша для бумаг', 7 => 'Личный шкафчик для вещей', 8 => 'Камера хранения', 9 => 'Ящик для личных вещей' ];
  	}
    public function getOptionsMealMap()
  	{
  		return [ 1 => 'Кухня', 2 => 'Кофе-брейк', 3 => 'Чай/кофе', 4 => 'Сладости' ];
  	}
    public function getOptionsRecrMap()
  	{
  		return [ 1 => 'Спортзал', 2 => 'Фитнесс-центр', 3 => 'Тренажеры', 4 => 'Настольный теннис' ];
  	}
    public function getOptionsTechMap()
  	{
  		return [ 1 => 'Интернет', 2 => 'Wi-fi', 3 => 'Канцелярские принадлежности' ];
  	}
    public function getOptionsMiscMap()
  	{
  		return [ 1 => 'Парковка', 2 => 'Личная страница на сайте', 3 => 'Секретарское обслуживание' ];
  	}

		private $_optionsList;
		public function getOptionsList()
		{
			if (!$this->_optionsList)
			{
					$arr = array();

					// if ($this->options)
					// 		foreach($this->options as $v)
					// 				$arr[] = $this->optionsMap[$v];

          if ($this->optionsStorage)
							foreach($this->optionsStorage as $v)
									$arr[] = $this->optionsStorageMap[$v];

          if ($this->optionsMeal)
							foreach($this->optionsMeal as $v)
									$arr[] = $this->optionsMealMap[$v];

          if ($this->optionsRecr)
							foreach($this->optionsRecr as $v)
									$arr[] = $this->optionsRecrMap[$v];

          if ($this->optionsTech)
							foreach($this->optionsTech as $v)
									$arr[] = $this->optionsTechMap[$v];

          if ($this->optionsMisc)
							foreach($this->optionsMisc as $v)
									$arr[] = $this->optionsMiscMap[$v];

          if ($this->options)
          {
              $arr_options = explode(',', $this->options);
              foreach ($arr_options as $v)
							    $arr[] = trim($v);
          }

					$this->_optionsList = $arr;
			}
			return $this->_optionsList;
		}
		public function getIssetOptions()
		{
				$this->getParamsList();
				$this->getOptionsList();
				return ( ( $this->_paramsList && count($this->_paramsList) > 0 ) ||
								 ( $this->_optionsList && count($this->_optionsList) > 0 ) ) ? true : false;
		}

    /////////////////////////////////////////////////////////////////////////////////////////////
    //Рендеринг элементов блока: расписание
    const MODE_NOINFO = 'нет информации';
  	const MODE_24X7 = 'круглосуточно';
  	const MODE_OFF = 'не работает';
    public function getModeMap()
  	{
  		return [ 0 => 'Не указано', 1 => 'Работает', 2 => 'Круглосуточно', 3 => 'Не работает' ];
  	}
  	public function getTimeMap()
  	{
  		return [ 0 => 'Неизвестно', 1 => '00:00', 2 => '00:30', 3 => '01:00', 4 => '01:30', 5 => '02:00', 6 => '02:30', 7 => '03:00', 8 => '03:30', 9 => '04:00', 10 => '04:30', 11 => '05:00', 12 => '05:30', 13 => '06:00', 14 => '06:30', 15 => '07:00', 16 => '07:30', 17 => '08:00', 18 => '08:30', 19 => '09:00', 20 => '09:30', 21 => '10:00', 22 => '10:30', 23 => '11:00', 24 => '11:30', 25 => '12:00', 26 => '12:30', 27 => '13:00', 28 => '13:30', 29 => '14:00', 30 => '14:30', 31 => '15:00', 32 => '15:30', 33 => '16:00', 34 => '16:30', 35 => '17:00', 36 => '17:30', 37 => '18:00', 38 => '18:30', 39 => '19:00', 40 => '19:30', 41 => '20:00', 42 => '20:30', 43 => '21:00', 44 => '21:30', 45 => '22:00', 46 => '22:30', 47 => '23:00', 48 => '23:30' ];
  	}

  	public function getTimeMap1()
  	{
  		$tmp = $this->timeMap;
  		$tmp[0] = 'с';
  		return $tmp;
  	}

  	public function getTimeMap2()
  	{
  		$tmp = $this->timeMap;
  		$tmp[0] = 'до';
  		return $tmp;
  	}
		private $_timetable;
		public function getTimetable()
		{
				if (!$this->_timetable)
				{
						$arr = array();
						for($i=1; $i<=7; $i++)
						{
								$str = $this->getModeString($i);
								if ($str)
										$arr[] = $str;
						}
						$this->_timetable = $arr;
				}
				return $this->_timetable;
		}
		public function getIssetTimetable()
		{
				$this->getTimetable();
				return ($this->_timetable && count($this->_timetable) > 0) ? true : false;
		}
    public function getWeekDay()
  	{
  		return [ 1 => 'Понедельник', 2 => 'Вторник', 3 => 'Среда', 4 => 'Четверг', 5 => 'Пятница', 6 => 'Суббота', 7 => 'Воскресенье' ];
  	}

  	public function getModeString($dayOfWeek)
  	{
  		$dayString = $this->weekDay[$dayOfWeek];
  		switch ($dayOfWeek)
  		{
  		case 1:
  			switch ($this->days_1_mode)
  			{
  			//case 0: return $dayString.': '.self::MODE_NOINFO;
  			case 1: if ($this->getTimeOpen($dayOfWeek) && $this->getTimeClose($dayOfWeek))
  						return $dayString.': с '.$this->getTimeOpen($dayOfWeek).' до '.$this->getTimeClose($dayOfWeek); break;
  			case 2: return $dayString.': '.self::MODE_24X7;
  			case 3: return $dayString.': '.self::MODE_OFF;
  			return '';
  			}
  		case 2:
  			switch ($this->days_2_mode)
  			{
  			//case 0: return $dayString.': '.self::MODE_NOINFO;
  			case 1: if ($this->getTimeOpen($dayOfWeek) && $this->getTimeClose($dayOfWeek))
  						return $dayString.': с '.$this->getTimeOpen($dayOfWeek).' до '.$this->getTimeClose($dayOfWeek); break;
  			case 2: return $dayString.': '.self::MODE_24X7;
  			case 3: return $dayString.': '.self::MODE_OFF;
  			return '';
  			}
  		case 3:
  			switch ($this->days_3_mode)
  			{
  			//case 0: return $dayString.': '.self::MODE_NOINFO;
  			case 1: if ($this->getTimeOpen($dayOfWeek) && $this->getTimeClose($dayOfWeek))
  						return $dayString.': с '.$this->getTimeOpen($dayOfWeek).' до '.$this->getTimeClose($dayOfWeek); break;
  			case 2: return $dayString.': '.self::MODE_24X7;
  			case 3: return $dayString.': '.self::MODE_OFF;
  			return '';
  			}
  		case 4:
  			switch ($this->days_4_mode)
  			{
  			//case 0: return $dayString.': '.self::MODE_NOINFO;
  			case 1: if ($this->getTimeOpen($dayOfWeek) && $this->getTimeClose($dayOfWeek))
  						return $dayString.': с '.$this->getTimeOpen($dayOfWeek).' до '.$this->getTimeClose($dayOfWeek); break;
  			case 2: return $dayString.': '.self::MODE_24X7;
  			case 3: return $dayString.': '.self::MODE_OFF;
  			return '';
  			}
  		case 5:
  			switch ($this->days_5_mode)
  			{
  			//case 0: return $dayString.': '.self::MODE_NOINFO;
  			case 1: if ($this->getTimeOpen($dayOfWeek) && $this->getTimeClose($dayOfWeek))
  						return $dayString.': с '.$this->getTimeOpen($dayOfWeek).' до '.$this->getTimeClose($dayOfWeek); break;
  			case 2: return $dayString.': '.self::MODE_24X7;
  			case 3: return $dayString.': '.self::MODE_OFF;
  			return '';
  			}
  		case 6:
  			switch ($this->days_6_mode)
  			{
  			//case 0: return $dayString.': '.self::MODE_NOINFO;
  			case 1: if ($this->getTimeOpen($dayOfWeek) && $this->getTimeClose($dayOfWeek))
  						return $dayString.': с '.$this->getTimeOpen($dayOfWeek).' до '.$this->getTimeClose($dayOfWeek); break;
  			case 2: return $dayString.': '.self::MODE_24X7;
  			case 3: return $dayString.': '.self::MODE_OFF;
  			return '';
  			}
  		case 7:
  			switch ($this->days_7_mode)
  			{
  			//case 0: return $dayString.': '.self::MODE_NOINFO;
  			case 1: if ($this->getTimeOpen($dayOfWeek) && $this->getTimeClose($dayOfWeek))
  						return $dayString.': с '.$this->getTimeOpen($dayOfWeek).' до '.$this->getTimeClose($dayOfWeek); break;
  			case 2: return $dayString.': '.self::MODE_24X7;
  			case 3: return $dayString.': '.self::MODE_OFF;
  			return '';
  			}
  		return '';
  		}
  	}

  	public function getTimeOpen($dayOfWeek)
  	{
  		switch ($dayOfWeek)
  		{
  		case 1:	if ($this->days_1_open) return $this->timeMap[$this->days_1_open];
  		case 2:	if ($this->days_2_open) return $this->timeMap[$this->days_2_open];
  		case 3:	if ($this->days_3_open) return $this->timeMap[$this->days_3_open];
  		case 4:	if ($this->days_4_open) return $this->timeMap[$this->days_4_open];
  		case 5:	if ($this->days_5_open) return $this->timeMap[$this->days_5_open];
  		case 6:	if ($this->days_6_open) return $this->timeMap[$this->days_6_open];
  		case 7:	if ($this->days_7_open) return $this->timeMap[$this->days_7_open];
  		return false;
  		}
  	}

  	public function getTimeClose($dayOfWeek)
  	{
  		switch ($dayOfWeek)
  		{
  		case 1:	if ($this->days_1_close) return $this->timeMap[$this->days_1_close];
  		case 2:	if ($this->days_2_close) return $this->timeMap[$this->days_2_close];
  		case 3:	if ($this->days_3_close) return $this->timeMap[$this->days_3_close];
  		case 4:	if ($this->days_4_close) return $this->timeMap[$this->days_4_close];
  		case 5:	if ($this->days_5_close) return $this->timeMap[$this->days_5_close];
  		case 6:	if ($this->days_6_close) return $this->timeMap[$this->days_6_close];
  		case 7:	if ($this->days_7_close) return $this->timeMap[$this->days_7_close];
  		return false;
  		}
  	}

    //////////////////////////////////////////////////////////////////////////////////////////////////
    // В модели Center есть поля, которые не задаются через админку, а вычисляются (например, is24x7).
    // Вот методы, которые используются при этих вычислениях:
    public function hasPrinter() {return $this->printer_mode == 2 ? false : true; }
    public function hasMeetingRoom() {return $this->meeting_room_mode == 2 ? false : true; }
    public function hasCourier() {return $this->courier_mode == 2 ? false : true; }
    public function hasGuest() {return $this->guest_mode == 2 ? false : true; }
    public function hasFixed() {return $this->is_fixed == 1 ? true : false; }
    public function hasStorage() {return is_array($this->optionsStorage) ? true : false; }
    public function is24x7()
    {
        if ( $this->days_1_mode == 2 && $this->days_2_mode == 2 && $this->days_3_mode == 2 && $this->days_4_mode == 2 && $this->days_5_mode == 2 && $this->days_6_mode == 2 && $this->days_7_mode == 2 )
            return true;
        else
            return false;
    }
}
