<?php

/** Tests BrandSiteConfigExtension */
class BrandSiteConfigExtensionTest extends SapphireTest {
    
    public function testUpdateFields() {
        
        $extension = new BrandSiteConfigExtension();
        
        $fields = FieldList::create([
            TabSet::create("Root", Tab::create("Main"))
        ]);
        
        $extension->updateCMSFields($fields);
        
        $this->assertNotNull($fields->fieldByName('Root.Main.BrandLogo'));
        $this->assertNotNull($fields->fieldByName('Root.Main.Favicon'));
    }
}
