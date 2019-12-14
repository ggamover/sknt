<?php


namespace App\classes;

/**
 * Class Request
 * @package App\classes
 *
 * @property string $method
 * @property array $path
 *
 * @property int $user_id
 * @property int $service_id
 *
 */
class Request
{
	const REQUEST_METHOD_GET = 'GET';
	const REQUEST_METHOD_PUT = 'PUT';

	protected $data;

	public function __construct()
	{
		$this->method = $_SERVER['REQUEST_METHOD'] ? $_SERVER['REQUEST_METHOD'] : 'GET';
		$this->parseUrl();
		if($this->method === self::REQUEST_METHOD_PUT){
			$this->loadInput();
		}
	}

	protected function parseUrl()
	{
		$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$absPath = $_SERVER['SCRIPT_FILENAME'];
		$prefix = str_replace($_SERVER['DOCUMENT_ROOT'], '', $absPath);
		$path = str_replace($prefix, '', $path);
		$path = trim($path, '/');
		$parts = explode('/', $path);
		$parts = array_filter($parts);
		$this->path = $parts;
	}

	public function getId($key)
	{
		$index = array_search($key, $this->path);
		if($index !== false && isset($this->path[$index + 1])){
			return $this->path[$index + 1];
		}
	}

	public function loadInput()
	{
		$input = file_get_contents('php://input');
		if($input){
			$this->data = json_decode($input, JSON_OBJECT_AS_ARRAY);
		}
	}

	public function getParam($key, $default = null)
	{
		if($this->data && isset($this->data[$key])){
			return $this->data[$key];
		}
		return $default;
	}
}
