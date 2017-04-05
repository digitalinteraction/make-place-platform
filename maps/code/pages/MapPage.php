<?php

/** ... */
class MapPage extends Page {
    
    
    protected $AddButton = "Add Pin";
    
    private static $db = [
        'StartLat' => 'Decimal(16, 8)',
        'StartLng' => 'Decimal(16, 8)',
        'StartZoom' => 'Int',
        'Tileset' => 'Enum(array("Google", "OpenStreet"), "Google")'
    ];
    
    private static $has_many = [
        'MapComponents' => 'MapComponent'
    ];
    
    private static $defaults = [
        'StartLat' => 54.978042,
        'StartLng' => -1.6136365,
        'StartZoom' => 15
    ];
    
    public static $page_fills_screen = false;
    
    
    public function getCMSFields() {
    
        $fields = parent::getCMSFields();
    
        $fields->addFieldsToTab('Root.Maps', [
            HeaderField::create('StartLabel', 'Initial Position'),
            DropdownField::create('Tileset', 'Tileset',
                singleton('MapPage')->dbObject('Tileset')->enumValues()
            ),
            FieldGroup::create([
                NumericField::create('StartLat', 'Latitude'),
                NumericField::create('StartLng', 'Longitude'),
                NumericField::create('StartZoom', 'Zoom')
            ])
        ]);
        
        $fields->addFieldsToTab('Root.Components', [
            GridField::create(
                'MapComponents',
                'Components',
                $this->MapComponents(),
                GridFieldConfig_RelationEditor::create()
            )
        ]);
    
        return $fields;
    }
}


/** ... */
class MapPage_Controller extends Page_Controller {
    
    private static $allowed_actions = [
        'mapConfig'
    ];
    
    
    
    public function mapConfig() {
        
        $config = SiteConfig::current_site_config();
        
        // Add our base values
        $json = [
            'page' => [
                'title' => $this->Title,
                'startLat' => $this->StartLat,
                'startLng' => $this->StartLng,
                'startZoom' => $this->StartZoom,
                'tileset' => $this->Tileset,
                'googleMapsKey' => $config->MapApiKey
            ],
            'components' => []
        ];
        
        // Add values for each component
        foreach ($this->MapComponents() as $component) {
            $json['components'][] = $component->configData();
        }
        
        
        $this->getResponse()->addHeader("Content-Type", "application/json");
        $this->getResponse()->setBody(json_encode($json));
        
        return $this->response;
    }
    
    public function getActions() {
        
        return ArrayList::create([
            [
                'Name' => 'Add Pin',
                'Icon' => 'fa-plus',
                'Handle' => 'pin-button',
                'Colour' => 'green'
            ],
            // [
            //     'Name' => 'Something',
            //     'Icon' => 'fa-meh-o',
            //     'Handle' => 'meh-ooooo',
            //     'Colour' => 'red'
            // ]
        ]);
    }
}
