<?php

/** A SiteConfig extension to add the brand logo field */
class BrandSiteConfigExtension extends DataExtension {
    
    private static $has_one = [
        'BrandLogo' => 'Image',
        'Favicon' => 'Image'
    ];
    
    public function updateCMSFields(FieldList $fields) {
        
        $fields->addFieldsToTab('Root.Main', [
            UploadField::create('BrandLogo', 'Brand Logo')
                ->setAllowedMaxFileNumber(1),
            UploadField::create('Favicon', 'Favicon')
                ->setAllowedMaxFileNumber(1)
        ]);
    }
}
