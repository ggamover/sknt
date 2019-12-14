<?php

namespace App\classes;

class Response
{
	/**
	 * поле result ответа приложения
	 * @var string
	 */
	protected $result = 'ok';
	/**
	 * данные в ответ приложения
	 * @var null:array
	 */
	protected $data;

	/**
	 * установить значение поля result
	 *
	 * @param bool $result
	 * @return $this
	 */
	public function setResult($result = true)
	{
		$this->result = $result ? 'ok' : 'error';
		return $this;
	}

	/**
	 * установить значение поля data
	 *
	 * @param $data
	 * @return $this
	 */
	public function setData($data)
	{
		$this->data = $data === null ? $data : ((array) $data);
		return $this;
	}

	/**
	 * отправить JSON ответ в браузер
	 */
	public function send()
	{
		$response = ['result' => $this->result];
		if(!empty($this->data)){
			$response = array_merge($response, $this->data);
		}
		$response = json_encode($response);
		header('Content-Type: application/json; charset=UTF-8');
		header('Content-Length: ' . strlen($response));
		echo $response;
	}
}