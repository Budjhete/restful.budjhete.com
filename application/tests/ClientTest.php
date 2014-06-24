<?php

class ClientTest extends Unittest_TestCase {
    
    public function testCreate() {
            
        $response = Request::factory('http://localhost:8080/company/test/client')    
            ->method(Request::PUT)
            ->execute();

        $this->assertEquals(302, $response->status());
    }

    /**
     * @depends testCreate
     */
    public function testFind($id) {
 
        $response = Request::factory("http://localhost:8080/company/test/client/$id")    
            ->execute();

        $this->assertEquals(200, $response->status());
    }

    /**
     * @depends testCreate
     */
    public function testUpdate($id) {
        
    }

    /**
     * @depends testCreate
     */
    public function testDelete($id) {
 
        // assert a 404 on find       
        $response = Request::factory("http://localhost:8080/company/test/client/$id")    
            ->execute();

        $this->assertEquals(404, $response->status());
    }

}
