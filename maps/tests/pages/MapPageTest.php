<?php

/** ... */
class MapPageTest extends SapphireTest {
    
    public function setUp() {
        parent::setUp();
        
        // ...
    }
    
    public function testCMSMapsTab() {
        
        // Create a test page
        $page = MapPage::create([]);
        
        // Get its fields
        $fields = $page->getCMSFields();
        
        // Test the maps tab was added
        $this->assertNotNull($fields->fieldByName('Root.Maps'));
    }
    
    public function testCMSComponentsTab() {
        
        // Create a test page
        $page = MapPage::create([]);
        
        // Get its fields
        $fields = $page->getCMSFields();
        
        // Test the maps tab was added
        $this->assertNotNull($fields->fieldByName('Root.Components'));
    }
}
