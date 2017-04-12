<?php

class GeoRefTest extends SapphireTest {
    
    protected $geoRef = null;
    protected $sampleResponse = null;
    
    public function setUp() {
        parent::setUp();
        
        $this->geoRef = GeoRef::create(["Reference" => 1]);
        $this->sampleResponse = [
            "meta" => [ "success" => true, "messages" => [] ],
            "data" => [
                "id" => 1,
                "geom" => [ "x" => 50.4, "y" => -1.1 ],
                "deployment_id" => 1,
                "data_type_id" => 1,
                "type" => "POINT"
            ]
        ];
        
        CurlRequest::$testResponse = json_encode($this->sampleResponse);
    }
    
    
    
    
    public function testInit() {
        
        $this->assertNotNull($this->geoRef);
    }
    
    public function testFetchValue() {
        
        $value = $this->geoRef->fetchValue();
        $this->assertEquals($value, $this->sampleResponse['data']);
    }
    
    public function testToJson() {
        
        $json = $this->geoRef->toJson();
        $this->assertEquals(array_keys($json), ['ID', 'Value']);
    }
}
