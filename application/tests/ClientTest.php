<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Tests related to client.
 *
 * This test the api, not the application itself.
 *
 * @package restful.budjhete.com
 * @category Tests
 * @author Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @copyright (c) 2014, Budj'hète Inc.
 */
class ClientTest extends Unittest_TestCase {

	public function testList()
	{
		$response = Request::factory('http://localhost:8080/company/test/clients')->headers('Authorization', 'Basic ' . base64_encode('test:test'))
			->method(Request::PUT)
			->execute();
		
		$this->assertEquals(200, $response->status(), $response->body());
		$this->assertEquals('application/json', $response->headers('Content-Type'));
	}

	public function testCreate()
	{
		$response = Request::factory('http://localhost:8080/company/test/client')->headers('Authorization', 'Basic ' . base64_encode('test:test'))
			->method(Request::PUT)
			->body(json_encode(array('nom' => 'James Morisson', 'langue' => 1)))
			->execute();
		
		$this->assertEquals(201, $response->status(), $response->body());
		$this->assertEquals('application/json', $response->headers('Content-Type'));
		
		$client = json_decode($response->body(), TRUE);
		
		$this->assertArrayHasKey('noClient', $client);
		
		return $client['noClient'];
	}

	public function testCreateMalformed()
	{
		$response = Request::factory('http://localhost:8080/company/test/client')->headers('Authorization', 'Basic ' . base64_encode('test:test'))
			->method(Request::PUT)
			->body('{')
			->execute();
		
		$this->assertEquals(400, $response->status(), $response->body());
		$this->assertEquals('application/json', $response->headers('Content-Type'));
	}

	/**
	 * @depends testCreate
	 *
	 * PUT /company/test/client
	 */
	public function testFind($id)
	{
		$response = Request::factory("http://localhost:8080/company/test/client/$id")->headers('Authorization', 'Basic ' . base64_encode('test:test'))
			->execute();
		
		$this->assertEquals(200, $response->status());
		$this->assertEquals('application/json', $response->headers('Content-Type'));
	}

	/**
	 * @depends testCreate
	 */
	public function testUpdate($id)
	{
		// assert a 404 on find
		$response = Request::factory("http://localhost:8080/company/test/client/$id")->headers('Authorization', 'Basic ' . base64_encode('test:test'))
			->method(Request::POST)
			->body(json_encode(array('nom' => 'James Morisson')))
			->execute();
		
		$this->assertEquals(200, $response->status());
		$this->assertEmpty($response->body());
		
		return $id;
	}

	/**
	 * @depends testUpdate
	 */
	public function testDelete($id)
	{
		// assert a 404 on find
		$response = Request::factory("http://localhost:8080/company/test/client/$id")->headers('Authorization', 'Basic ' . base64_encode('test:test'))
			->method(Request::DELETE)
			->execute();
		
		$this->assertEquals(204, $response->status());
	}
}
