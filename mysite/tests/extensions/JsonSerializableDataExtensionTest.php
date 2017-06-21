<?php



class MockJsonDataObject extends DataObject {
    private static $db = [ "Name" => "Varchar(255)", "Secret" => "Varchar(255)" ];
    private static $excluded_fields = [ "Secret" ];
}
class MockJsonDataObjectSubclass extends MockJsonDataObject {
    private static $db = [ "Secret2" => "Varchar(255)" ];
    private static $excluded_fields = [ "Secret2" ];
}
class MockInclusiveJsonDataObject extends DataObject {
    private static $db = [ "PropA" => "Varchar(255)", "PropB" => "Varchar(255)", "PropC" => "Varchar(255)" ];
    private static $included_fields = [ "PropC" ];
}



/** Tests JsonSerializableDataExtension */
class JsonSerializableDataExtensionTest extends SapphireTest {
    
    public $usesDatabase = true;
    
    public function testSerialize() {
        
        $object = MockJsonDataObject::create([
            "Name" => "Bobby", "Secret" => "4H9Qv*Q@9766"
        ]);
        
        $object->write();
        
        $json = $object->jsonSerialize();
        
        $keys = array_keys($json);
        
        $this->assertContains('id', $keys);
        $this->assertContains('created', $keys);
        $this->assertContains('name', $keys);
    }
    
    public function testExcludedFields() {
        
        $object = MockJsonDataObject::create([
            "Name" => "Bobby", "Secret" => "4H9Qv*Q@9766"
        ]);
        
        $object->write();
        
        $json = $object->jsonSerialize();
        
        $this->assertNotContains('secret', array_keys($json));
    }
    
    public function testExludedFieldsWithInheritance() {
        
        $object = MockJsonDataObjectSubclass::create([
            "Name" => "Bobby",
            "Secret" => "4H9Qv*Q@9766",
            "Secret2" => "9hYLK1fC*1w"
        ]);
        
        $object->write();
        
        $json = $object->jsonSerialize();
        
        $this->assertNotContains('secret', array_keys($json));
        $this->assertNotContains('secret2', array_keys($json));
    }
    
    public function testIncludedFields() {
        
        $object = MockInclusiveJsonDataObject::create([
            "PropA" => "A", "PropB" => "B", "PropC" => "C"
        ]);
        
        $object->write();
        
        $json = $object->jsonSerialize();
        
        $expected = [
            "propC" => "C"
        ];
        
        $this->assertNotContains('propA', array_keys($json));
        $this->assertNotContains('propB', array_keys($json));
        $this->assertContains('propC', array_keys($json));
    }
}
