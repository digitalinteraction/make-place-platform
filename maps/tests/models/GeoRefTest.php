<?php

/** Tests GeoRef */
class GeoRefTest extends SapphireTest {
    
    protected $geoRef = null;
    protected $sampleResponse = null;
    
    public $usesDatabase = true;
    
    public function setUp() {
        parent::setUp();
        
        $this->geoRef = GeoRef::create(["Reference" => 1]);
        $this->sampleResponse = [
            "meta" => [ "success" => true, "messages" => [] ],
            "data" => [
                "id" => 1,
                "geom" => [ "x" => "50", "y" => "-1" ],
                "deployment_id" => 1,
                "data_type_id" => 1,
                "type" => "POINT"
            ]
        ];
    }
    
    
    
    
    public function testInit() {
        
        $this->assertNotNull($this->geoRef);
    }
    
    
    public function testFetchValue() {
        
        CurlRequest::$testResponse = json_encode($this->sampleResponse);
        
        $value = $this->geoRef->fetchValue();
        $this->assertEquals($this->sampleResponse['data'], $value);
    }
    
    public function testToJson() {
        
        CurlRequest::$testResponse = json_encode($this->sampleResponse);
        
        $json = $this->geoRef->toJson();
        $this->assertEquals(['ID', 'Value'], array_keys($json));
    }
    
    
    
    public function testMakeRef() {
        
        // What the geo api should return
        CurlRequest::$testResponse = json_encode([
            "meta" => [ "success" => 1, "messages" => [] ],
            "data" => 7
        ]);
        
        // Create a reference
        $ref = GeoRef::makeRef("POINT", 1, [
            "x" => "50", "y" => "-1"
        ]);
        
        // Check it set the reference property
        $this->assertEquals(7, $ref->Reference);
    }
    
    
    
}
