<?php
/*
 *  Tests JsonFieldExtension
 */

class JsonFieldExMockObject extends Object {
    
    protected $field;
    
    function __construct($field) {
        $this->field = $field;
        parent::__construct();
    }
    
    function dbObject($name) { return $this->field; }
}


/** Tests JsonFieldExtension */
class JsonFieldExtensionTest extends SapphireTest {
    
    protected $field = null;
    protected $object = null;
    
    // Mock a DataObject's field accessor
    function dbObject($name) { return $this->field; }
    
    
    public function setUpOnce() {
        
        parent::setUpOnce();
        
        // Add the extension to ourself
        // Only need to do this once, not before each test
        JsonFieldExMockObject::add_extension('JsonFieldExtension');
    }
    
    
    public function setUp() {
        
        parent::setUp();
        
        // Create a field for testing
        $this->field = new JsonText("test_field");
        
        // Create an object to test the extension with
        $this->object = new JsonFieldExMockObject($this->field);
    }
    
    public function testInit() {
        
        // Test the field is alive
        $this->assertNotNull($this->field);
        
        // Test the object is alive
        $this->assertNotNull($this->object);
    }
    
    public function testGetValue() {
        
        // Some json to test with
        $json = [
            "key" => "value"
        ];
        
        // Set the value of our field
        $this->field->setValue($json);
        
        
        // Set the text value onto our object,
        // Like it would be set on a DataObject
        $this->object->TestField = json_encode($json);
        
        
        // Check our helper gets the json
        $this->assertEquals($json, $this->object->jsonField('TestField'));
    }
}
