<?php

/** ... */
class MapComponent extends DataObject {
    
    private static $db = [
        'Name' => 'Varchar(255)'
    ];
    
    private static $has_one = [
        'Page' => 'MapPage'
    ];
    
    private static $summary_fields = [
        'Name' => 'Name',
        'ClassName' => 'Component'
    ];
    
    public function getCMSFields() {
        
        $fields = FieldList::create([
            TabSet::create('Root', Tab::create('Main'))
        ]);
        
        
        // Add the default fields
        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Name', 'Name')
        ]);
        
        
        // Get the subclass types
        $types = $this->availableTypes();
        
        
        // If there are no subclasses, don't add more fields
        if (count($types) == 0) {
            return $fields;
        }
        
        
        // Add the type field
        $fields->addFieldsToTab('Root.Main', [
            DropdownField::create('ClassName', 'Type', $types)
                ->setDescription("You will need to save for this property to update")
        ]);
        
        
        if ($this->ID == null) {
            return $fields;
        }
        
        // Let the sub-types add their own fields
        $this->addExtraFields($fields);
        
        
        return $fields;
    }
    
    public function getTitle() {
        return $this->ClassName;
    }
    
    public function availableTypes() {
        return ClassUtils::getSubclasses('MapComponent', 'MapComponent');
    }
    
    public function addExtraFields(FieldList $fields) {
        // Override in subclasses
    }
    
    
    public function configData() {
        return [
            'type' => $this->ClassName
        ];
    }
}
