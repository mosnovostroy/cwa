<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\FileHelper;

class CenterFeatures extends \yii\base\Model
{

	const MODE_NOINFO = 'нет информации';
	const MODE_24X7 = 'круглосуточно';
	const MODE_OFF = 'не работает';
	
	public $id;
	public $isTariff = 0;
	
	//Название тарифа
	 public $name;
	
	//Цены
	public $price_minute;
	public $price_hour;
	public $price_day;
	public $price_week;
	public $price_month;
	
	//Закреплено ли рабочее место
	public $is_fixed;
	
	//Место для хранения
	public $options = [0,0,0,0,0];
	public function getOptionsMap()
	{
		return [ 1 => 'Сейф', 2 => 'Ящик', 3 => 'Шкаф', 4 => 'Шкафчик', 5 => 'Wi-fi' ];
	}

	//Принтер
	public $printer_pages;
	public $printer_mode;
	public function getPrinterModeMap()
	{
		return [ 0 => 'Неизвестно', 1 => 'Не более N страниц в день', 2 => 'Нет', 3 => 'Не ограничено', 4 => 'Предоставляется' ];
	}


	//Переговорная, количество бесплатных часов за период
	public $meeting_room_hours;
	public $meeting_room_mode;
	public function getMeetingRoomModeMap()
	{
		return [ 0 => 'Неизвестно', 1 => 'Не более N часов в день', 2 => 'Не более N часов в неделю', 3 => 'Не более N часов в месяц', 4 => 'Нет', 5 => 'Не ограничено', 6 => 'Предоставляется' ];
	}

	
	//Режим работы и часы работы
	public $days_1_mode;
	public $days_2_mode;
	public $days_3_mode;
	public $days_4_mode;
	public $days_5_mode;
	public $days_6_mode;
	public $days_7_mode;
	
	public function getModeMap()
	{
		return [ 0 => 'Неизвестно', 1 => 'Работает', 2 => 'Круглосуточно', 3 => 'Не работает' ];
	}

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
		$tmp[0] = 'по';
		return $tmp;
	}		

    public function rules()
    {
        return [
			[['id'], 'integer'],
			[['name'], 'string'],
            [['price_minute', 'price_hour', 'price_day', 'price_week', 'price_month'], 'integer'],
			[['is_fixed'], 'integer'],
			[['options'], 'each', 'rule' => ['integer']],
			[['printer_pages', 'printer_mode'], 'integer'],
			[['meeting_room_hours', 'meeting_room_mode'], 'integer'],
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
			'is_fixed' => 'Рабочее место закрепляется',
			'options' => 'Предоставляется',
			'price_minute' => 'Цена за минуту', 
			'price_hour' => 'Цена за час', 
			'price_day' => 'Цена за день', 
			'price_week' => 'Цена за неделю', 
			'price_month' => 'Цена за месяц',
			'printer_mode' => 'Принтер', 
			'meeting_room_mode' => 'Переговорная',
			'days_1_mode' => 'Понедельник',
			'days_2_mode' => 'Вторник',
			'days_3_mode' => 'Среда',
			'days_4_mode' => 'Четверг',
			'days_5_mode' => 'Пятница',
			'days_6_mode' => 'Суббота',
			'days_7_mode' => 'Воскресенье',
			];
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
			case 0: return $dayString.': '.self::MODE_NOINFO;
			case 1: if ($this->getTimeOpen($dayOfWeek) && $this->getTimeClose($dayOfWeek))
						return $dayString.': с '.$this->getTimeOpen($dayOfWeek).' по '.$this->getTimeClose($dayOfWeek); break;
			case 2: return $dayString.': '.self::MODE_24X7;
			case 3: return $dayString.': '.self::MODE_OFF;
			return '';
			}
		case 2:	
			switch ($this->days_2_mode)
			{
			case 0: return $dayString.': '.self::MODE_NOINFO;
			case 1: if ($this->getTimeOpen($dayOfWeek) && $this->getTimeClose($dayOfWeek))
						return $dayString.': с '.$this->getTimeOpen($dayOfWeek).' по '.$this->getTimeClose($dayOfWeek); break;
			case 2: return $dayString.': '.self::MODE_24X7;
			case 3: return $dayString.': '.self::MODE_OFF;
			return '';
			}
		case 3:	
			switch ($this->days_3_mode)
			{
			case 0: return $dayString.': '.self::MODE_NOINFO;
			case 1: if ($this->getTimeOpen($dayOfWeek) && $this->getTimeClose($dayOfWeek))
						return $dayString.': с '.$this->getTimeOpen($dayOfWeek).' по '.$this->getTimeClose($dayOfWeek); break;
			case 2: return $dayString.': '.self::MODE_24X7;
			case 3: return $dayString.': '.self::MODE_OFF;
			return '';
			}
		case 4:	
			switch ($this->days_4_mode)
			{
			case 0: return $dayString.': '.self::MODE_NOINFO;
			case 1: if ($this->getTimeOpen($dayOfWeek) && $this->getTimeClose($dayOfWeek))
						return $dayString.': с '.$this->getTimeOpen($dayOfWeek).' по '.$this->getTimeClose($dayOfWeek); break;
			case 2: return $dayString.': '.self::MODE_24X7;
			case 3: return $dayString.': '.self::MODE_OFF;
			return '';
			}
		case 5:	
			switch ($this->days_5_mode)
			{
			case 0: return $dayString.': '.self::MODE_NOINFO;
			case 1: if ($this->getTimeOpen($dayOfWeek) && $this->getTimeClose($dayOfWeek))
						return $dayString.': с '.$this->getTimeOpen($dayOfWeek).' по '.$this->getTimeClose($dayOfWeek); break;
			case 2: return $dayString.': '.self::MODE_24X7;
			case 3: return $dayString.': '.self::MODE_OFF;
			return '';
			}
		case 6:	
			switch ($this->days_6_mode)
			{
			case 0: return $dayString.': '.self::MODE_NOINFO;
			case 1: if ($this->getTimeOpen($dayOfWeek) && $this->getTimeClose($dayOfWeek))
						return $dayString.': с '.$this->getTimeOpen($dayOfWeek).' по '.$this->getTimeClose($dayOfWeek); break;
			case 2: return $dayString.': '.self::MODE_24X7;
			case 3: return $dayString.': '.self::MODE_OFF;
			return '';
			}
		case 7:	
			switch ($this->days_7_mode)
			{
			case 0: return $dayString.': '.self::MODE_NOINFO;
			case 1: if ($this->getTimeOpen($dayOfWeek) && $this->getTimeClose($dayOfWeek))
						return $dayString.': с '.$this->getTimeOpen($dayOfWeek).' по '.$this->getTimeClose($dayOfWeek); break;
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
	

}
