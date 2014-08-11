<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 */
class Controller_Client extends Controller_Template {

	public $client;

	public function before()
	{
		parent::before();

		$this->client = RESTful::factory('Client', $this->request->param('id'));
	}

	public function action_index()
	{
		
	}

	public function action_create()
	{
		$this->client
				->values($this->request->post())
				->create();
	}

	public function action_update()
	{
		$this->client
				->values($this->request->post())
				->update();
	}

	public function action_delete()
	{
		$this->client->delete();
	}

	public function after()
	{
		$this->template->client = $this->client;
		parent::after();
	}

}
