<?php

namespace App\classes;

class Response
{
	protected $result = 'ok';
	protected $data;

	public function setResult($result = true)
	{
		$this->result = $result ? 'ok' : 'error';
		return $this;
	}

	public function setData($data)
	{
		$this->data = $data === null ? $data : ((array) $data);
		return $this;
	}

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