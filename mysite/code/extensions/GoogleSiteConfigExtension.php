<?php

/** An extension for SiteConfig to add google keys & codes */
class GoogleSiteConfigExtension extends DataExtension {
  
    private static $db = [
        'AnalyticsCode' => 'HtmlText'
    ];
    
    public function updateCMSFields(FieldList $fields) {
        
        $fields->addFieldsToTab('Root.Main', [
            TextareaField::create('AnalyticsCode', 'Analytics Code')
        ]);
    }
}
