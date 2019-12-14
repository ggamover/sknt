<?php


namespace App\classes;


use App\classes\model\Service;
use App\classes\model\Tarif;

class Sknt
{
	public function run()
	{
		$request = new Request();
		$userId = (int)$request->getId('users');
		$serviceId = (int)$request->getId('services');

		switch($request->method){
			case Request::REQUEST_METHOD_GET:
				$service = Service::load($userId, $serviceId);
				$tarif = Tarif::load($service->tarif_id);
				(new Response)->send([
					'tarifs' => [[
						'title' => $tarif->title,
						'link' => $tarif->link,
						'speed' => $tarif->speed,
						'tarifs' => Tarif::getList($tarif->tarif_group_id)
					]]
				]);
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
