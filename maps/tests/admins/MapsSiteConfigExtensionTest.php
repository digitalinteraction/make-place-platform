<?php

class MockMapsSiteConfig extends DataObject {
    
}

/** ... */
class MapsSiteConfigExtensionTest extends SapphireTest {
    
    public function setUpOnce() {
        parent::setUpOnce();
        
        MockMapsSiteConfig::add_extension('MapsSiteConfigExtension');
    }
    
    public function testExtensionAdded() {
        
        // Check the extension could be added
        $this->assertTrue(MockMapsSiteConfig::has_extension('MapsSiteConfigExtension'));
    }
    
    public function testUpdateCMSFields() {
        
        // Create some fields to add to
        $fields = FieldList::create(TabSet::create('Root'));
        
        
        // Make the config add the fields to our list
        $config = MockMapsSiteConfig::create();
        $config->updateCMSFields($fields);
        
        
        // Get a field that should be added
        $field = $fields->fieldByName('Root.Maps.MapApiKey');
        
        
        // Check the field was added
        $this->assertNotNull($field);
    }
}
