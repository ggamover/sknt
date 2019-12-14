<?php

namespace App\classes;

class Response
{
	protected $result = 'ok';

	public function result($result = true)
	{
		$this->result = $result ? 'ok' : 'error';
		return $this;
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