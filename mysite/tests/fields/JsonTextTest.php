<?php
/*
 *  Tests JsonText field type
 */
class JsonTextTest extends SapphireTest {
    
    protected $field = null;
    
    
    public function setUp() {
        
        parent::setUp();
        
        // Create a field to test
        $this->field = new JsonText("test_field");
    }
    
    public function testInit() {
        
        // Check the field was created
        $this->assertNotNull($this->field);
    }
    
    public function testSetValue() {
        
        // Some json to test with
        $json = [
            "key" => "value"
        ];
        
        // The encoded json (how it should be written tot he db)
        $expected = json_encode($json);
        
        // Set the json onto the field
        $this->field->setValue($json);
        
        // Test the field encoded the json into it's value
        $this->assertEquals($expected, $this->field->value);
    }
    
    public function testGetJson() {
        
        // Some json to test with
        $json = [
            "key" => "value"
        ];
        
        // Set the json onto the field
        $this->field->setValue($json);
        
        // Get the json back from our field
        $result = $this->field->valueAsJson();
        
        // Test we got the same json back
        $this->assertEquals($json, $result);
    }
}
