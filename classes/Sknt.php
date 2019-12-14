<?php
namespace App\classes;


use App\classes\model\Service;
use App\classes\model\Tarif;

/**
 * Class Sknt
 *
 * Тестовое задание по вакансии
 *
 * @package App\classes
 */

class Sknt
{
	public function run()
	{
		$request = new Request();
		$userId = (int)$request->getId('users');
		$serviceId = (int)$request->getId('services');

		$response = (new Response);
		switch($request->method){
			case Request::REQUEST_METHOD_GET:
				$data = $this->getTarifList($userId, $serviceId);
				$response->setData($data);
				break;
			case Request::REQUEST_METHOD_PUT:
				if(!$this->setTarif($request, $userId, $serviceId)){
					$response->setResult(false);
				}
				break;
		}
		$response->send();
		exit;
	}

	/**
	 * @return Db
	 */
	public static function getDbo()
	{
		return new Db(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	}

	/**
	 * @param $msg
	 */
	public function sendError($msg){
		(new Response())->setResult(false)
			->setData(['error' => $msg])
			->send();
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

	/**
	 * @param Request $request
	 * @param int $userId
	 * @param int $serviceId
	 * @return array
	 */
	public function setTarif(Request $request, int $userId, int $serviceId)
	{
		$tarifId = $request->getParam('tarif_id');
		$tarif = Tarif::load($tarifId);
		if ($tarif === false) {
			$this->sendError('could not load tarif');
		}

		$service = Service::load($userId, $serviceId);
		if ($service === false) {
			$this->sendError('could not load service');
//
//					$service = new Service;
//					$service->user_id = $userId;
		}

		$service->tarif_id = $tarif->ID;
		$service->setPaydayByMonthNum($tarif->pay_period);

		return $service->save();
	}

}
