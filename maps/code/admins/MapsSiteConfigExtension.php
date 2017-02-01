<?php

/** ... */
class MapsSiteConfigExtension extends DataExtension {
    
    private static $db = [
        'MapApiKey' => 'Varchar(255)'
    ];
    
    public function updateCMSFields(FieldList $fields) {
        
        $apiLink = 'https://console.developers.google.com/apis/api/maps_backend/overview';
        
        $fields->addFieldsToTab('Root.Maps', [
            TextField::create('MapApiKey', 'Google Maps API Key'),
            LiteralField::create('Key info',
                "<h3 style='text-align:center'> <a href=$apiLink target='_blank'> Generate a key</a> </h3>"
            )
        ]);
        
    }
}
