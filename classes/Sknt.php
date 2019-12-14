<?php
namespace App\classes;


use App\classes\model\Service;
use App\classes\model\Tarif;

/**
 * Class Sknt
 *
 * Тестовое задание по вакансии
 *
 * Главный класс приложения, он же контроллер
 *
 * @package App\classes
 */

class Sknt
{
	/**
	 * Стартовый метод
	 *
	 */
	public function run()
	{
//		Получить входные данные
		$request = new Request();
//		код пользователя
		$userId = (int)$request->getId('users');
//		код сервиса
		$serviceId = (int)$request->getId('services');

//		создать объект для вывода результатов
		$response = (new Response);

//		выбрать одно из двух действий согласно ТЗ
//		поскольку действий всего 2, опустим дополнительные проверки
//		выбираем по типу HTTP запроса
		switch($request->method){
			case Request::REQUEST_METHOD_GET:
//				вернуть тариф с опциями
				$data = $this->getTarifList($userId, $serviceId);
				$response->setData($data);
				break;
			case Request::REQUEST_METHOD_PUT:
//				установить новый тариф
				if(!$this->setTarif($request, $userId, $serviceId)){
					$response->setResult(false);
				}
				break;
		}

//		отправить ответ
		$response->send();

//		п/п.7 п. "Общие требования к результату" я не понял, возможно имелось ввиду явное завершение
		exit;
	}

	/**
	 * создать и вернуть объект подключения к БД
	 *
	 * @return Db
	 */
	public static function getDbo()
	{
		return new Db(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	}

	/**
	 * отправить ошибку
	 * и завершить работу
	 *
	 * @param $msg
	 */
	public function sendError($msg){
		(new Response())->setResult(false)
			->setData(['error' => $msg])
			->send();
		exit;
	}

	/**
	 * Загрузить и вернуть данные по тарифам
	 *
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
	 * сменить данные тарифа в заданном сервисе
	 *
	 * @param Request $request
	 * @param int $userId
	 * @param int $serviceId
	 * @return array
	 */
	public function setTarif(Request $request, int $userId, int $serviceId)
	{
//		получить код тарифа из тела запроса
		$tarifId = $request->getParam('tarif_id');
//		загрузить тариф из БД
		$tarif = Tarif::load($tarifId);

//		если нет тарифа отправить ошибку
		if ($tarif === false) {
			$this->sendError('could not load tarif');
		}
// 		загрузить данные сервиса
//		по логике ТЗ новый сервис мы не создаём, только меняем имеющийся
		$service = Service::load($userId, $serviceId);
		if ($service === false) {
			$this->sendError('could not load service');
//		а если бы надо было создать новый, то поступили бы так
//					$service = new Service;
//					$service->user_id = $userId;
		}

//		установить новый код тарифа
		$service->tarif_id = $tarif->ID;

//		установить дату платежа
		$service->setPaydayByMonthNum($tarif->pay_period);

//		записать всё в БД и вернуть результат
		return $service->save();
	}

}
