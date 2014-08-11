<?php
defined('SYSPATH') or die('No direct script access.');

/**
 *
 * @package restful.budjhete.com
 * @category Models
 * @author Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @copyright (c) 2014, Budj'ète Inc.
 */
class RESTful extends Model {

	/**
	 * Model name.
	 *
	 * @var string
	 */
	protected $_name = NULL;

	/**
	 * Key used for primary key.
	 *
	 * @var string
	 */
	protected $_primary_key = NULL;

	/**
	 * Model data
	 *
	 * @var array
	 */
	private $_data = array();
	
	/**
	 * 
	 * @var Response
	 */
	private $_last_response;

	public function __construct()
	{
		if ($this->_name === NULL)
		{
			$this->_name = strtolower(substr(get_class($this), strlen('Model_')));
		}
		
		if ($this->_primary_key === NULL)
		{
			$this->_primary_key = 'id';
		}
	}

	/**
	 * Fetch multiple models.
	 * 
	 * @throws RESTful_Exception
	 * @return array
	 */
	public function find_all()
	{
		$this->_last_response = Request::factory('http://localhost:8080')
			->headers('Authorization', 'Basic ' . base64_encode($api_key))
			->execute();
		
		if ($this->_last_response->status() !== 200)
		{
			throw new RESTful_Exception($this->_last_response->body());
		}
		
		return json_decode($this->_last_response->body(), TRUE);
	}

	/**
	 * Fetch the model.
	 * 
	 * @return \RESTful
	 */
	public function find()
	{
		$url = Route::get('restful')->uri(array(
			'model' => $this->_name,
			'id' => $this->_data[$this->_primary_key]
		));
		
		$this->_last_response = Request::factory('http://localhost:8080')
			->headers('Authorization', 'Basic ' . base64_encode($api_key))
			->execute();
		
		if ($this->_last_response->status() !== 200)
		{
			throw new RESTful_Exception($this->_last_response->body());
		}
		
		$this->_data = json_decode($this->_last_response->body(), TRUE);
		
		return $this;
	}

	/**
	 * Create the model.
	 * 
	 * @return \RESTful
	 */
	public function create()
	{
		$url = Route::get('restful')->uri(array(
			'model' => $this->_name,
		));
		
		$this->_last_response = Request::factory('http://localhost:8080')->method(Request::PUT)
			->headers('Authorization', 'Basic ' . base64_encode($api_key))
			->body(json_encode($this->_data))
			->execute();
		
		if ($this->_last_response->status() !== 201)
		{
			throw new RESTful_Exception($this->_last_response->body());
		}
		
		$this->_data = json_decode($this->_last_response->body(), TRUE);
		
		return $this;
	}

	/**
	 * Update the model.
	 * 
	 * @return \RESTful
	 */
	public function update()
	{
		$url = Route::get('restful')->uri(array(
			'model' => $this->_name,
			'id' => $this->_data[$this->_primary_key]
		));
		
		$this->_last_response = Request::factory('http://localhost:8080')->method(Request::POST)
			->headers('Authorization', 'Basic ' . base64_encode($api_key))
			->body(json_encode($this->data))
			->execute();
		
		if ($this->_last_response->status() !== 200)
		{
			throw new RESTful_Exception($this->_last_response->body());
		}
		
		return $this;
	}

	/**
	 * Delete the model.
	 */
	public function delete()
	{
		$url = Route::get('restful')->uri(array(
			'model' => $this->_name,
			'id' => $this->_data[$this->_primary_key]
		));
		
		$this->_last_response = Request::factory($url)
			->headers('Authorization', 'Basic ' . base64_encode($api_key))
			->execute();
		
		if ($this->_last_response->status() !== 200)
		{
			throw new RESTful_Exception($this->_last_response->body());
		}
	}

	/**
	 * 
	 * @param array $values
	 * @param array $expected
	 * @return \RESTful
	 */
	public function values(array $values, array $expected = NULL)
	{
		$this->_data = Arr::merge($this->_data, Arr::extract($array, $expected));
		
		return $this;
	}
	
	/**
	 * Get the last executed request Response object.
	 * 
	 * @return Response
	 */
	public function last_response() 
	{
		return $this->_last_response;
	}

	public function __get($name)
	{
		return $this->_data[$name];
	}

	public function __set($name, $value)
	{
		$this->_data[$name] = $value;
	}

	public function __isset($name)
	{
		return isset($this->_data[$name]);
	}

	public function __unset($name)
	{
		unset($this->_data[$name]);
	}
	
	public function __toString() 
	{
		return $this->_data[$this->_primary_key];
	}
}