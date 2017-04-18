<?php

class MockCurlRequest extends CurlRequest {
    
    // ...
}

class CurlRequestTest extends SapphireTest {
    
    protected $request = null;
    
    public function setUp() {
        parent::setUp();
        
        $this->request = MockCurlRequest::create("127.0.0.1", ["key" => "value"]);
    }
    
    public function testInit() {
        
        // Check request was created
        $this->assertNotNull($this->request);
        
        
        // Check params were set
        $this->assertEquals("127.0.0.1", $this->request->getUrl());
        $this->assertEquals(["key" => "value"], $this->request->getGetVars());
    }
    
    public function testDoubleExecutionCaches() {
        
        // TODO: Call twice doesn't do 2 requests
    }
    
    public function testConstructUrl() {
        
        // Check url was constructed correctly
        $this->assertEquals("127.0.0.1?key=value", $this->request->constructUrl());
    }
    
    
    
    public function testJsonResponse() {
        
        // The json we want it to return
        $expected = [ "test" => "value" ];
        CurlRequest::$testResponse = json_encode($expected);
        
        // Perform the request
        $json = $this->request->jsonResponse();
        
        // Check it decoded the json
        $this->assertEquals($expected, $json);
    }
    
    
    
    public function testValidApiResponse() {
        
        $response = [
            'meta' => [
                'success' => true,
                'messages' => []
            ],
            'data' => "Hello, world"
        ];
        
        $this->assertTrue(CurlRequest::validApiResponse($response));
    }
    
    public function testValidApiResponseRequiresMeta() {
        
        // A response without the meta
        $response = [
            'data' => "Hello, world"
        ];
        
        $this->assertFalse(CurlRequest::validApiResponse($response));
    }
    
    public function testValidApiResponseRequiresMetaSuccess() {
        
        // A response without the meta
        $response = [
            'meta' => [
                'messages' => []
            ],
            'data' => "Hello, world"
        ];
        
        $this->assertFalse(CurlRequest::validApiResponse($response));
    }
    
    public function testValidApiResponseRequiresData() {
        
        // A response without the meta
        $response = [
            'meta' => [
                'success' => true,
                'messages' => []
            ],
        ];
        
        $this->assertFalse(CurlRequest::validApiResponse($response));
    }
    
    public function testValidApiResponseRequiresSuccess() {
        
        $response = [
            'meta' => [
                'success' => false,
                'messages' => []
            ],
            'data' => "Hello, world"
        ];
        
        $this->assertFalse(CurlRequest::validApiResponse($response));
    }
    
}
