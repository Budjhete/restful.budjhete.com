<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Calls the api
 */
class BudjheteRESTfulTest extends Unittest_TestCase {

    /**
     * List available companies
     */
    public function testCompaniesUnauthenticated() {

        $response = Request::factory('localhost:8080/companies')->execute();

        $this->assertEquals(200, $response->status());
        $this->assertJSONStringEqualsJSONString('["test"]', $response->body());
    }

    /**
     * List available companies
     */
    public function testCompanies() {

        $response = Request::factory('localhost:8080/companies')->execute();

        $this->assertEquals(200, $response->status());
        $this->assertJSONStringEqualsJSONString('["test"]', $response->body());
    }

    /**
     * Access a specific company unauthenticated
     */
    public function testAccessCompanyUnauthenticated() {

        $response = Request::factory('localhost:8080/company/test')
                ->execute();

        $this->assertEquals(401, $response->status());
        $this->assertEquals('Basic realm="Budj\'hète RESTful api"', $response->headers('WWW-Authenticate'));
        $this->assertEmpty($response->body());
    }

    /**
     * 
     */
    public function testCompany() {

        $response = Request::factory('localhost:8080/company/test')
                ->headers('Authorization', 'Basic ' . base64_encode('test:test'))
                ->execute();

        $this->assertEquals(200, $response->status());
        $this->assertEquals('application/json', $response->headers('Content-Type'));

        $this->assertJSONStringEqualsJSONString('', $response->body());
    }

}
