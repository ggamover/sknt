<?php


namespace App\classes;


use PDO;

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
		return $this->connection->query($query);
	}
}