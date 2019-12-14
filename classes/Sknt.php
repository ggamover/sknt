<?php


namespace App\classes;


use App\classes\model\Service;
use App\classes\model\Tarif;

class Sknt
{
	public function run()
	{
		$request = new Request();
		$userId = $request->getId('users');
		$serviceId = $request->getId('services');

		switch($request->method){
			case Request::REQUEST_METHOD_GET:
				$service = Service::load($userId, $serviceId);
				(new Response)->send(['tarifs' => Tarif::getList($service->tarif_id)]);
				break;
			case Request::REQUEST_METHOD_PUT:

				break;
		}
		exit;
	}

	/**
	 * @return Db
	 */
	public static function getDbo()
	{
		return new Db(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	}

}
