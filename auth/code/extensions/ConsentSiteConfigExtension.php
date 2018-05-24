<?php

class ConsentSiteConfigExtension extends DataExtension {
    
    private static $db = [
        'ConsentEffectiveDate' => 'Date',
        'ConsentContactInfo' => 'HTMLText'
    ];
    
    public function updateCMSFields(FieldList $fields) {
        
        // Add extra CMS Fields
        $fields->addFieldsToTab('Root.Consent', [
            DateField::create('ConsentEffectiveDate', 'Last Consent Change'),
            HtmlEditorField::create('ConsentContactInfo', 'Consent Contact Info')
        ]);
    }
}
