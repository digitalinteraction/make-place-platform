<?php

class MockMapComponent extends MapComponent {
    
    public function extraFields() {
        return [ TextField::create('TestField', 'TestField') ];
    }
}

/** ... */
class MapComponentTest extends SapphireTest {
    
    protected $component = null;
    
    public function setUp() {
        
        parent::setUp();
        
        $this->component = MapComponent::create([]);
    }
    
    
    public function testInit() {
        
        $this->assertNotNull($this->component);
    }
    
    public function testGetCMSFields() {
        
        $fields = $this->component->getCMSFields(true);
        $this->assertTrue($fields->count() > 0);
    }
    
    public function testGetTitle() {
        
        $this->assertEquals('MapComponent', $this->component->getTitle());
    }
    
    
    
    
    public function testAvailableTypes() {
        
        $types = $this->component->availableTypes();
        
        $this->assertTrue(count($types) > 0);
    }
    
    public function testDefaultExtraFields() {
        
        $this->assertEquals(0, count($this->component->extraFields()));
    }
    
    public function testAddsExtraFields() {
        
        $mockComp = MockMapComponent::create();
        
        // Set the id to mock an init
        $mockComp->ID = 1;
        
        // Get the fields
        $fields = $mockComp->getCMSFields();
        
        // Find the field that should have been added
        $extraField = $fields->fieldByName('Root.Main.TestField');
        
        // Check the field was added
        $this->assertNotNull($extraField, "Extrafields not added");
    }
    
    public function testAddsExtraFieldsWithoutInit() {
        
        $mockComp = MockMapComponent::create();
        
        // Get the fields
        $fields = $mockComp->getCMSFields();
        
        // Find the field that should have been added
        $extraField = $fields->fieldByName('Root.Main.TestField');
        
        // Check the field was added
        $this->assertNull($extraField, "Extrafields not added");
    }
    
    
}
