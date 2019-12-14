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
}