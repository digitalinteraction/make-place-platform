<?php

/** Tests CurlRequest */
class CurlRequestTest extends SapphireTest {
    
    protected $request = null;
    
    public function setUp() {
        parent::setUp();
        
        $this->request = CurlRequest::create("127.0.0.1", ["key" => "value"]);
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
    
    
    public function testApiResponseErrors() {
        
        $response = [
            'meta' => [
                'messages' => [ "A", "B"]
            ]
        ];
        
        $this->assertEquals(["A", "B"], CurlRequest::apiResponseErrors($response));
    }
    
    public function testApiResponseErrorsUnknown() {
        
        $response = [
            'meta' => [
            ]
        ];
        
        $this->assertEquals(["An unknown error occurred"], CurlRequest::apiResponseErrors($response));
    }
    
    
    public function testPostMethod() {
        
        $this->request->setMethod("POST");
        
        $options = $this->request->curlOptions();
        
        $this->assertTrue(isset($options[CURLOPT_POST]));
    }
    
    public function testRequestBody() {
        
        $this->request->setBody("Body");
        
        $options = $this->request->curlOptions();
        
        $this->assertEquals("Body", $options[CURLOPT_POSTFIELDS]);
    }
    
    public function testRequestBodyFromJson() {
        
        $this->request->setJsonBody(["Key" => "Value"]);
        
        $options = $this->request->curlOptions();
        
        
        // Check the body was set as a json string
        $this->assertEquals('{"Key":"Value"}', $options[CURLOPT_POSTFIELDS]);
        
        
        // Check json headers were set
        $this->assertContains("Content-Type: application/json", $options[CURLOPT_HTTPHEADER]);
        $this->assertContains("Content-Length: 15", $options[CURLOPT_HTTPHEADER]);
    }
    
    public function testAddHeader() {
        
        $this->request->addHeader("Key", "Value");
        
        $options = $this->request->curlOptions();
        
        $this->assertEquals(["Key: Value"], $options[CURLOPT_HTTPHEADER]);
    }
    
    
    
    
    
    public function testGeo() {
        
        // $req = CurlRequest::create(GEO_URL."/geo", ["api_key" => GEO_KEY]);
        // $req->setMethod("POST");
        // $req->setJsonBody([
        //     "geom" => [
        //         "type" => "POINT",
        //         "x" => "50",
        //         "y" => "1"
        //     ],
        //     "data_type" => "1"
        // ]);
        // print_r($req->jsonResponse());
    }
}
