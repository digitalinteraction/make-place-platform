<?php

/** Adds a temporal filter to the Map */
class TemporalFilterMapComponent extends MapComponent {
    
    private static $db = [
        'Mode' => 'Enum(array("DayOfWeek", "DateRange", "Recentness", "TimeOfDay"), "DayOfWeek")'
    ];
    
    public function addExtraFields(FieldList $fields) {
        
        $types = singleton('TemporalFilterMapComponent')->dbObject('Mode')->enumValues();
        
        $fields->addFieldsToTab('Root.Main', [
            DropdownField::create('Mode', 'Filter Mode', $types)
        ]);
    }
}
