<?php


namespace App\classes;

/**
 * Class Request
 * @package App\classes
 *
 * класс для получения входящих данных
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

//	используемые методы запроса
	const REQUEST_METHOD_GET = 'GET';
	const REQUEST_METHOD_PUT = 'PUT';

	/**
	 * свойство для параметров из тела запроса
	 *
	 * @var null|array
	 */
	protected $data;


	/**
	 * Request constructor.
	 */
	public function __construct()
	{
//		определить метод HTPP запроса из параметров PHP
		$this->method = $_SERVER['REQUEST_METHOD'] ? $_SERVER['REQUEST_METHOD'] : 'GET';
		$this->parseUrl();
		if($this->method === self::REQUEST_METHOD_PUT){
			$this->loadInput();
		}
	}

	/**
	 * разобрать адресную строку для получения
	 * параметров запроса
	 *
	 */
	protected function parseUrl()
	{
//		взять весь путь из URL
		$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
//		получить абсолютный путь к скрипту в файловой системе
		$absPath = $_SERVER['SCRIPT_FILENAME'];
//		определить подкаталог, если он есть
		$prefix = str_replace($_SERVER['DOCUMENT_ROOT'], '', $absPath);
//		и удалить его
		$path = str_replace($prefix, '', $path);
//		убрать крайние символы /
		$path = trim($path, '/');
//		оставшуюся часть разделить по слэшам
		$parts = explode('/', $path);
//		убрать пустые части, если есть
		$parts = array_filter($parts);
//		записать в свойство класса
		$this->path = $parts;
	}

	/**
	 * получить параметр из URL по имени
	 *
	 * @param $key
	 * @return mixed
	 */
	public function getId($key)
	{
//		найти индекс имени в массиве
		$index = array_search($key, $this->path);
		if($index !== false && isset($this->path[$index + 1])){
//			вернуть следующее значение из массива,
//			например, если URL /users/2/services/3, то по имени services вернёт 3
			return $this->path[$index + 1];
		}
	}

	/**
	 *  получить все данные из тела запроса
	 */
	public function loadInput()
	{
		$input = file_get_contents('php://input');
		if($input){
			$this->data = json_decode($input, JSON_OBJECT_AS_ARRAY);
		}
	}

	/**
	 * получить параметр из тела запроса по имени
	 *
	 * @param $key
	 * @param null $default
	 * @return mixed|null
	 */
	public function getParam($key, $default = null)
	{
		if($this->data && isset($this->data[$key])){
			return $this->data[$key];
		}
		return $default;
	}
}
