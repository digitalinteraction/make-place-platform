<?php

/** A page that renders a map and adds logic through MapComponents */
class MapPage extends Page {
    
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
    
    
    /** Used by silverstripe to render fields to edit an instance of MapPage */
    public function getCMSFields() {
    
        $fields = parent::getCMSFields();
        
        // A config for sortable components
        $config = GridFieldConfig_RelationEditor::create()
            ->removeComponentsByType('GridFieldAddExistingAutocompleter')
            ->removeComponentsByType('GridFieldExportButton')
            ->removeComponentsByType('GridFieldPrintButton')
            ->removeComponentsByType('GridFieldDeleteAction')
            ->removeComponentsByType('GridFieldFilterHeader')
            ->addComponent(new GridFieldSortableRows('Order'))
            ->addComponent(new GridFieldDeleteAction());
        
        $fields->removeByName('Content');
    
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
                $config
            )
        ]);
    
        return $fields;
    }
}


/** A controller to render a MapPage */
class MapPage_Controller extends Page_Controller {
    
    
    /** the url actions on the controller, calls a function of the same name */
    private static $allowed_actions = [
        'mapConfig'
    ];
    
    
    /** A endpoint relative to the page its on, exposes map config for js to render and compose logic */
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
        
        
        // Make the response json
        $this->getResponse()->addHeader("Content-Type", "application/json");
        $this->getResponse()->setBody(json_encode($json));
        
        
        // Return the response
        return $this->response;
    }
    
    
    /** Overrides default relation to sort components by their order */
    public function MapComponents() {
        return parent::MapComponents()->sort('Order');
    }
}
