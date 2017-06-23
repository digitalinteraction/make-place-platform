<?php

/** Tests GeoQuestion */
class GeoQuestionTest extends SapphireTest {
    
    public $usesDatabase = true;
    protected $question = null;
    
    
    
    /* Test Lifecycle */
    public function setUp() {
        parent::setUp();
        
        $this->question = GeoQuestion::create([
            "Name" => "Geo Question",
            "Handle" => "geo-question",
            "GeoType" => "POINT",
            "DataType" => 1,
            "Required" => 1
        ]);
    }
    
    
    
    /* Test Properties */
    public function testInit() {
        
        $this->assertNotNull($this->question);
    }
    
    public function testExtraFields() {
        
        $extras = $this->question->extraFields();
        
        $this->assertEquals(2, count($extras));
    }
    
    
    
    /* Test Validation */
    public function testNonRequiredValidation() {
        
        $this->question->Required = false;
        $errors = $this->question->validateValue(null);
        
        $this->assertEquals(0, count($errors));
    }
    
    public function testRequiredValidation() {
        
        $errors = $this->question->validateValue(null);
        
        $this->assertEquals(1, count($errors));
    }
    
    public function testValidatePointValue() {
        
        $errors = $this->question->validateValue([
            "x" => "50",
            "y" => "1"
        ]);
        
        $this->assertEquals(0, count($errors));
    }
    
    public function testValidatePointRequiresAnX() {
        
        $errors = $this->question->validateValue([
            "y" => "1"
        ]);
        
        $this->assertEquals(1, count($errors));
    }
    
    public function testValidatePointRequiresAY() {
        
        $errors = $this->question->validateValue([
            "x" => "50"
        ]);
        
        $this->assertEquals(1, count($errors));
    }
    
    public function testValidatePointIsNumeric() {
        
        $errors = $this->question->validateValue([
            "x" => "abc",
            "y" => "xyz"
        ]);
        
        $this->assertEquals(2, count($errors));
    }
    
    public function testValidateWithGeoRefId() {
        
        // Create our geo ref
        $ref = GeoRef::create([]);
        $ref->write();
        
        // Try to pack it
        $errors = $this->question->validateValue($ref->ID);
        
        // Check there were no issues
        $this->assertEquals(0, count($errors));
    }
    
    public function testValidateWithGeoRefIdChecksRef() {
    
        // Try to pack an invalid geo id
        $errors = $this->question->validateValue("10");
    
        // Check there were no issues
        $this->assertEquals(1, count($errors));
    }
    
    
    
    /* Test Value Packing */
    public function testPackValue() {
        
        // The value to pack
        $value = [ "x" => "50", "y" => "10" ];
        
        
        // The geo response we're expecting
        CurlRequest::$testResponse = json_encode([
            "meta" => [ "success" => 1, "messages" => [] ],
            "data" => "1"
        ]);
        
        
        // How many refs currently exist in the database
        // NOTE: SS is a bit dogey with cleaning the db between tests
        $prevRefs = GeoRef::get()->count();
        
        // Pack the value, should create a new GeoRef
        $packed = $this->question->packValue($value);
        
        // How many refs there now are
        $refs = GeoRef::get()->count() - $prevRefs;
        
        // Check a ref was created
        $this->assertEquals(1, $refs);
    }
    
    public function testPackValueWithId() {
        
        // Create our geo ref
        $ref = GeoRef::create([]);
        $ref->write();
        
        // Try to pack it
        $packed = $this->question->packValue($ref->ID);
        
        // Check it returned our ref's id
        $this->assertEquals($ref->ID, $packed);
    }
    
    public function testUnpackValue() {
        
        // The reference we want it to fetch
        $ref = GeoRef::create([ "Reference" => "2" ]);
        $ref->write();
        
        
        // The data we want to to return
        $expected = [
            "id" => 2,
            "geom" => [ "x" => "51", "y" => "-1" ],
            "type" => "POINT",
            "deployment_id" => 1,
            "data_type_id" => 1
        ];
        
        
        // The geo response it should expect
        CurlRequest::$testResponse = json_encode([
            "meta" => [ "success" => 1, "messages" => [] ],
            "data" => $expected
        ]);
        
        // Unpack the GeoRef (its id is stored in the json)
        $value = $this->question->unpackValue($ref->ID);
        
        
        $this->assertEquals($expected, $value);
        
    }
    
    
    
    /* Misc */
    public function testResponseCreated() {
        
        // Create a geo ref to relate to
        $ref = GeoRef::create(["Ref" => 1]);
        $ref->write();
        
        $response = SurveyResponse::create([]);
        $response->write();
        
        $this->question->responseCreated($response, $ref->ID);
        
        $this->assertEquals(1, $response->Geometries()->count());
        $this->assertEquals(1, $ref->Responses()->count());
    }
    
    
}
