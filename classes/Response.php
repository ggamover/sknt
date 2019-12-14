<?php

namespace App\classes;

class Response
{
	protected $result;

	public function __construct($result = true)
	{
		$this->result = $result ? 'ok' : 'fail';
	}

	public function send($data)
	{
		$response = array_merge(['result' => $this->result], $data);
		$response = json_encode($response);
		header('Content-Type: application/json; charset=UTF-8');
		header('Content-Length: ' . strlen($response));
		echo $response;
	}
}