<?php

/** A SiteConfig extension to add the maps api key field */
class MapsSiteConfigExtension extends DataExtension {
    
    private static $db = [
        'MapApiKey' => 'Varchar(255)'
    ];
    
    public function updateCMSFields(FieldList $fields) {
        
        // The link for where to get an api key
        $apiLink = 'https://console.developers.google.com/apis/api/maps_backend/overview';
        
        // Add the field to enter the api key
        // Plus a link to generate one
        $fields->addFieldsToTab('Root.Maps', [
            TextField::create('MapApiKey', 'Google Maps API Key'),
            LiteralField::create('Key info',
                "<h3 style='text-align:center'> <a href=$apiLink target='_blank'> Generate a key</a> </h3>"
            )
        ]);
        
    }
}
