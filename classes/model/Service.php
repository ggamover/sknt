<?php


namespace App\classes\model;

use App\classes\Sknt;

/**
 * Class Service
 * @package App\classes\model
 *
 * Модель для таблицы services
 *
 * @property int $ID
 * @property int $user_id
 * @property int $tarif_id
 * @property string $payday
 *
 */
class Service
{
	/**
	 * Получить запись по коду
	 *
	 * @param $userId
	 * @param $serviceId
	 * @return Service
	 */
	public static function load($userId, $serviceId)
	{
		$dbo = Sknt::getDbo();
		$query = [
			'SELECT * FROM `services`',
			'WHERE user_id = :user_id',
			'AND ID = :id'
		];

		$statement = $dbo->query($query, [
			'user_id' => $userId,
			'id' => $serviceId
		]);

		return $statement->fetchObject(static::class);

	}

	/**
	 * записать текущую модель в БД
	 *
	 * @return false|\PDOStatement
	 */
	public function save()
	{
		$dbo = Sknt::getDbo();
		$params = [
			'id' => $this->ID,
			'tarif_id' => $this->tarif_id,
			'payday' => $this->payday
		];

		if($this->ID){
//			если есть ID, то обновление
			$sql = $this->updateSql();
		}else{
//			иначе создание
			$sql = $this->insertSql();
			$params['user_id'] = $this->user_id;
		}

		$result = $dbo->query($sql, $params);
		return $result;
	}

	/**
	 * создать SQL запрос на вставку
	 *
	 * @return array
	 */
	protected function insertSql()
	{
		return [
			'INSERT INTO `services`',
			'(`user_id`, `tarif_id`, `payday`)',
			'VALUES (:user_id, :tarif_id, :payday)',
		];

	}

	/**
	 * создать SQL запрос на обновление
	 * @return array
	 */
	protected function updateSql()
	{
		return [
			'UPDATE `services`',
			'SET `tarif_id` = :tarif_id,',
			'`payday` = :payday',
			'WHERE ID = :id'
		];
	}

	/**
	 * расчитать и установить дату платежа
	 *
	 * @param $monthNum месяцев до платежа
	 * @throws \Exception
	 */
	public function setPaydayByMonthNum($monthNum)
	{
//		текущая дата
		$now = new \DateTime('now', new \DateTimeZone('Europe/Moscow'));
//		установить полночь
		$now->setTime(0,0,0);
//		добавить заданное количество месяцев
		$now->modify("+{$monthNum} months");
//		записать результат в свойство модели
		$this->payday = $now->format('Y-m-d');
	}
}