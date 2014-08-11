<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @package restful.budjhete.com
 */
class Controller_Company extends Controller_Template {

	public function action_index()
	{
		$api_key = Kohana::$config->load('restful.api_key');

		$response = Request::factory('http://localhost:8080/companies')
				->headers('Authorization', 'Basic ' . base64_encode($api_key))
				->execute();

		$this->template->companies = json_decode($response->body(), TRUE);
	}

}
