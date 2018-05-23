<?php

/** A SiteConfig extension that adds the option to toggle login & registration */
class AuthSiteConfigExtension extends DataExtension {
    
    private static $db = [
        'LoginDisabled' => 'Boolean',
        'RegisterDisabled' => 'Boolean'
    ];
    
    public function updateCMSFields(FieldList $fields) {
        
        // Add fields to toggle auth
        $fields->addFieldsToTab('Root.Auth', [
            CheckboxField::create('LoginDisabled', 'Disable Login'),
            CheckboxField::create('RegisterDisabled', 'Disable Registration')
        ]);
    }
}
