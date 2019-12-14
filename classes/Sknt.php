<?php


namespace App\classes;


class Sknt
{
	public function run()
	{
		$dbo = new Db(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$rows = [];
		foreach ($dbo->query('SELECT * FROM tarifs ORDER BY `title`') as $row){
			$rows[] = $row;
		}
		var_dump($rows);

		exit();
	}
}