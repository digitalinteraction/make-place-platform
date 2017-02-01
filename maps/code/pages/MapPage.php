<?php

/** ... */
class MapPage extends Page {
    
    
    protected $AddButton = "Add Pin";
    
    private static $db = [
        'StartLat' => 'Decimal(16, 8)',
        'StartLng' => 'Decimal(16, 8)',
        'StartZoom' => 'Int'
    ];
    
    private static $has_many = [
        'MapComponents' => 'MapComponent'
    ];
    
    private static $defaults = [
        'StartLat' => 54.978042,
        'StartLng' => -1.6136365,
        'StartZoom' => 15
    ];
    
    
    public function getCMSFields() {
    
        $fields = parent::getCMSFields();
    
        $fields->addFieldsToTab('Root.Maps', [
            HeaderField::create('StartLabel', 'Initial Position'),
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
    
    // ...
}
