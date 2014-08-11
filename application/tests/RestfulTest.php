<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Test the api by calling it directly.
 *
 * You must have a running instance of Budj'hète and a company named test. Also,
 * there must be a user named test having a password called test.
 *
 * @package restful.budjhete.com
 * @category Tests
 * @author Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @copyright (c) 2014, Budj'hète Inc.
 */
class RESTfulTest extends Unittest_TestCase {

	public function testIndex()
	{
		$api_key = Kohana::$config->load('restful.api_key');
		
		$response = Request::factory('http://localhost:8080')
			->headers('Authorization', 'Basic ' . base64_encode($api_key))
			->execute();
		
		$this->assertEquals(200, $response->status(), $response->body());
		$this->assertEquals('application/json', $response->headers('Content-Type'));
		
		$configuration = json_decode($response->body(), TRUE);
		
		$this->assertArrayHasKey('Companies', $configuration);
	}

	public function testIndexWithTrailingSlash()
	{
		$api_key = Kohana::$config->load('restful.api_key');
		
		$response = Request::factory('http://localhost:8080/')
			->headers('Authorization', 'Basic ' . base64_encode($api_key))
			->execute();
		
		$this->assertEquals(200, $response->status(), $response->body());
		$this->assertEquals('application/json', $response->headers('Content-Type'));
	}

	/**
	 * List available companies
	 *
	 * GET /companies
	 */
	public function testCompaniesUnauthenticated()
	{
		$response = Request::factory('http://localhost:8080/companies')->execute();
		
		$this->assertEquals(401, $response->status(), $response->body());
	}

	/**
	 * List available companies
	 *
	 * GET /companies
	 */
	public function testCompanies()
	{
		$api_key = Kohana::$config->load('restful.api_key');
		
		$response = Request::factory('http://localhost:8080/companies')
			->headers('Authorization', 'Basic ' . base64_encode($api_key))
			->execute();
		
		$this->assertEquals(200, $response->status());
		$this->assertEquals('application/json', $response->headers('Content-Type'));
		
		$companies = json_decode($response->body(), TRUE);
		
		$this->assertContains('test', Arr::pluck($companies, 'Name'), print_r(Arr::pluck($companies, 'Name'), TRUE));
	}

	/**
	 * 
	 */
	public function testCompaniesUsingWrongApiKey()
	{
		$api_key = uniqid() . ':' . uniqid();
		
		$response = Request::factory('http://localhost:8080/companies')
			->headers('Authorization', 'Basic ' . base64_encode($api_key))
			->execute();
		
		$this->assertEquals(401, $response->status());
		$this->assertEquals('Basic realm="Budj\'hète RESTful api"', $response->headers('WWW-Authenticate'));
	}
	
	/**
	 *
	 */
	public function testCompaniesUsingInvalidApiKey()
	{
		$api_key = uniqid() . ':';
	
		$response = Request::factory('http://localhost:8080/companies')
			->headers('Authorization', 'Basic ' . base64_encode($api_key))
			->execute();
	
		$this->assertEquals(401, $response->status());
		$this->assertEquals('Basic realm="Budj\'hète RESTful api"', $response->headers('WWW-Authenticate'));
	}

	/**
	 * Get software version, which is the api version.
	 * 
	 * GET /version
	 */
	public function testVersion()
	{
		$api_key = Kohana::$config->load('restful.api_key');
		
		$response = Request::factory('http://localhost:8080/version')
			->headers('Authorization', 'Basic ' . base64_encode($api_key))
			->execute();
		
		$this->assertEquals(200, $response->status());
		$this->assertEquals('application/json', $response->headers('Content-Type'));
		$this->assertJSONStringEqualsJSONString('"2.4"', $response->body());
	}

	/**
	 * Access a specific company unauthenticated
	 *
	 * GET /company/<name>
	 */
	public function testAccessCompanyUnauthenticated()
	{
		$response = Request::factory('http://localhost:8080/company/test')->execute();
		
		$this->assertEquals(401, $response->status());
		$this->assertEquals('Basic realm="Budj\'hète RESTful api"', $response->headers('WWW-Authenticate'));
		$this->assertEmpty($response->body());
	}

	/**
	 * Access a specific company.
	 *
	 * This endpoint returns JSON object containing the company preferences.
	 *
	 * GET /company/<name>
	 */
	public function testCompany()
	{
		$response = Request::factory('http://localhost:8080/company/test')
			->headers('Authorization', 'Basic ' . base64_encode('test:test'))
			->execute();
		
		$this->assertEquals(200, $response->status());
		$this->assertEquals('application/json', $response->headers('Content-Type'));
		
		$company = json_decode($response->body(), TRUE);
		
		$this->assertArrayHasKey('Nom', $company, $response->body());
	}
}
