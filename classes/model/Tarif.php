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
	 * @param $tarifId
	 * @return Tarif
	 */
	public static function load($tarifId)
	{
		$dbo = Sknt::getDbo();
		$query = [
			'SELECT * FROM `tarifs`',
			'WHERE ID = :id',
		];

		$statement = $dbo->query($query, [
			'id' => $tarifId
		]);

		return $statement->fetchObject(static::class);

	}

	/**
	 * @param $userId
	 * @param $serviceId
	 *
	 * @return Tarif[]
	 */
	public static function getList($groupId)
	{
		$dbo = Sknt::getDbo();

//		день след. оплаты
		$payDayExpr = implode(PHP_EOL, [
			'CONCAT(',
				'UNIX_TIMESTAMP(', // вывод в timestamp
					'ADDDATE(TIMESTAMP(NOW()), INTERVAL pay_period MONTH)', // прибавим pay_period к этой полночи
				'),',
				"IF(NOW() > UTC_TIMESTAMP, '+', ''),", // подправим знак сравнив текущее время с гринвичем
				"TIME_FORMAT(TIMEDIFF(NOW(), UTC_TIMESTAMP), '%H:%i')", // разница времени с гринвичем
			')'
		]);

		$sql = [
			'SELECT',
			implode(',', [
				'ID',
				'title',
				'ROUND(`price`) AS `price`',
				'pay_period',
				 $payDayExpr . ' AS `new_payday`',
				'speed'
			]),
			'FROM `tarifs`'
		];

		if($groupId){
			$sql[] = 'WHERE `tarif_group_id` = :group_id';
		}

		$statement = $dbo->query($sql, ['group_id' => $groupId]);

		$rows = [];
		while ($row = $statement->fetchObject(static::class)){
			$rows[] = $row;
		}

		return $rows;
	}


}
