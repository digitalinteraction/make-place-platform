<?php

/** Tests GoogleSiteConfigExtension */
class GoogleSiteConfigExtensionTest extends SapphireTest {
    
    public function testUpdateFields() {
        
        $extension = new GoogleSiteConfigExtension();
        
        $fields = FieldList::create([
            TabSet::create("Root", Tab::create("Main"))
        ]);
        
        $extension->updateCMSFields($fields);
        
        $field = $fields->fieldByName('Root.Main.AnalyticsCode');
        
        $this->assertNotNull($field);
    }
}
