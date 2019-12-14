<?php


namespace App\classes;

/**
 * Class Request
 * @package App\classes
 *
 * @property string $method
 * @property array $path
 */
class Request
{
	const REQUEST_METHOD_GET = 'GET';
	const REQUEST_METHOD_PUT = 'PUT';

	public function __construct()
	{
		$this->method = $_SERVER['REQUEST_METHOD'] ? $_SERVER['REQUEST_METHOD'] : 'GET';
		$this->path = $this->parseUrl();
	}

	protected function parseUrl()
	{
		$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$absPath = $_SERVER['SCRIPT_FILENAME'];
		$prefix = str_replace($_SERVER['DOCUMENT_ROOT'], '', $absPath);
		$path = str_replace($prefix, '', $path);
		$path = trim($path, '/');
		$parts = explode('/', $path);
		return array_filter($parts);
	}
}
