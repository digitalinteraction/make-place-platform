<?php

class MockMapComponent extends MapComponent {
    
    public function addExtraFields(FieldList $fields) {
        
        $fields->addFieldsToTab('Root.Main', [
            TextField::create('TestField', 'TestField')
        ]);
    }
}

/** Tests MapComponent */
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
    
    
    
    
    public function testDefaultJson() {
        
        $expected = [
            'type' => 'MapComponent'
        ];
        
        $this->assertArraySubset($expected, $this->component->jsonSerialize());
    }
}
