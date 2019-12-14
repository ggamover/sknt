<?php


namespace App\classes\model;


use App\classes\Sknt;

/**
 * Class Tarif
 * @package App\classes\model
 *
 * @property int $ID
 * @property string $title
 * @property float $price
 * @property string $link
 * @property int $speed
 * @property int $pay_period
 * @property int $tarif_group_id
 *
 */
class Tarif
{
	/**
	 * @param $userId
	 * @param $serviceId
	 *
	 * @return Tarif[]
	 */
	public static function getList($tarifId)
	{
		$dbo = Sknt::getDbo();

		$sql = [
			'SELECT * FROM `tarifs`',
		];

		if($tarifId){
			$sql[] = 'WHERE `tarif_group_id` = (SELECT `tarif_group_id` FROM `tarifs` WHERE ID = :id)';
		}

		$statement = $dbo->query($sql, ['id' => $tarifId]);

		$rows = [];
		while ($row = $statement->fetchObject(static::class)){
			$rows[] = $row;
		}

		return $rows;
	}



}
