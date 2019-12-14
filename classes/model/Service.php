<?php


namespace App\classes\model;

use App\classes\Sknt;

/**
 * Class Service
 * @package App\classes\model
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

	public function save()
	{
		$dbo = Sknt::getDbo();
		$params = [
			'id' => $this->ID,
			'tarif_id' => $this->tarif_id,
			'payday' => $this->payday
		];
		if($this->ID){
			$sql = $this->updateSql();
		}else{
			$sql = $this->insertSql();
			$params['user_id'] = $this->user_id;
		}

		$result = $dbo->query($sql, $params);
		return $result;
	}

	protected function insertSql()
	{
		return [
			'INSERT INTO `services`',
			'(`user_id`, `tarif_id`, `payday`)',
			'VALUES (:user_id, :tarif_id, :payday)',
		];

	}

	protected function updateSql()
	{
		return [
			'UPDATE `services`',
			'SET `tarif_id` = :tarif_id,',
			'`payday` = :payday',
			'WHERE ID = :id'
		];
	}

	public function setPaydayByMonthNum($monthNum)
	{
		$now = new \DateTime('now', new \DateTimeZone('Europe/Moscow'));
		$now->setTime(0,0,0);
		$now->modify("+{$monthNum} months");
		$this->payday = $now->format('Y-m-d');
	}
}