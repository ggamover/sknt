<?php


namespace App\classes;


use PDO;

/***
 * Class Db
 *
 * класс для работы с БД при помощи PDO
 *
 * @package App\classes
 */

class Db
{
	private $connection;

	public function __construct($host, $user, $pwd, $dbName, $charset = 'utf8')
	{
		$connection = new PDO("mysql:host={$host};dbname={$dbName};charset={$charset}", $user, $pwd);
		$this->connection = $connection;
	}

	/**
	 * @param $query
	 * @param array $params
	 * @return false|\PDOStatement
	 */
	public function query($query, $params = [])
	{
		if(is_array($query)){
			$query = implode(PHP_EOL, $query);
		}

		$statement = $this->connection->prepare($query);
		$statement->execute($params);

		return $statement;
	}

}
