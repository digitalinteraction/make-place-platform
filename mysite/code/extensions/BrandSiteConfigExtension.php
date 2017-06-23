<?php

/** A SiteConfig extension to add the brand logo field */
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
