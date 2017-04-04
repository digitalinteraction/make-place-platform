<?php

/** ... */
class BrandSiteConfigExtension extends DataExtension {
    
    private static $has_one = [
        'BrandLogo' => 'Image'
    ];
    
    public function updateCMSFields(FieldList $fields) {
        
        $fields->addFieldsToTab('Root.Main', [
            UploadField::create('BrandLogo', 'Brand Logo')
        ]);
    }
}
