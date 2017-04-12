<?php

class BrandSiteConfigExtensionTest extends SapphireTest {
    
    public function testUpdateFields() {
        
        $extension = new BrandSiteConfigExtension();
        
        $fields = FieldList::create([
            TabSet::create("Root", Tab::create("Main"))
        ]);
        
        $extension->updateCMSFields($fields);
        
        $field = $fields->fieldByName('Root.Main.BrandLogo');
        
        $this->assertNotNull($field);
    }
}
