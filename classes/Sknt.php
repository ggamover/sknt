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
				(new Response)->send($this->getTarifList($userId, $serviceId));
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

	public function sendError($msg){
		(new Response())->setResult(false)->send(['error' => $msg]);
		exit;
	}

	/**
	 * @param int $userId
	 * @param int $serviceId
	 *
	 * @return array
	 */
	public function getTarifList(int $userId, int $serviceId)
	{
		$service = Service::load($userId, $serviceId);
		$data = ['tarifs' => []];
		if ($service !== false) {
			$tarif = Tarif::load($service->tarif_id);
			if ($tarif === false) {
				$this->sendError('could not load tarif');
			}
			$data['tarifs'][] = [
					'title' => $tarif->title,
					'link' => $tarif->link,
					'speed' => $tarif->speed,
					'tarifs' => Tarif::getList($tarif->tarif_group_id)
			];
		}
		return $data;
	}

}
