<?php

/** The building blocks of the map, used to compose logic through the CMS */
class MapComponent extends DataObject {
    
    private static $db = [
        'Name' => 'Varchar(255)',
        'Order' => 'Int'
    ];
    
    private static $has_one = [
        'Page' => 'MapPage'
    ];
    
    private static $summary_fields = [
        'Name' => 'Name',
        'ClassName' => 'Component',
        'Page.Title' => 'Page'
    ];
    
    /** Used by silverstripe to generate fields to edit the component */
    public function getCMSFields() {
        
        // Start with a set of tabs
        $fields = FieldList::create([
            TabSet::create('Root', Tab::create('Main'))
        ]);
        
        
        // Add the default fields
        $fields->addFieldsToTab('Root.Main', [
            HeaderField::create("GeneralHeader", "Component Info"),
            TextField::create('Name', 'Name')
        ]);
        
        
        // Get the subclass types
        $types = $this->availableTypes();
        
        
        // If there are no subclasses, don't add more fields
        if (count($types) == 0) { return $fields; }
        
        
        // Add the type field
        $fields->addFieldsToTab('Root.Main', [
            DropdownField::create('ClassName', 'Type', $types)
                ->setDescription("The type of component to add <br> NOTE: You will need to save for this property to update")
        ]);
        
        
        // If not created, don't add any more fields
        if ($this->ID == null) { return $fields; }
        
        
        // Let the sub-types add their own fields
        $this->addExtraFields($fields);
        
        
        // Return the generated fields
        return $fields;
    }
    
    /** The title used by the cms */
    public function getTitle() {
        return $this->ClassName;
    }
    
    /** Gets the available subclasses */
    public function availableTypes() {
        return ClassUtils::getSubclasses('MapComponent', 'MapComponent', true);
    }
    
    /** @codeCoverageIgnore */
    public function addExtraFields(FieldList $fields) {
        // Override in subclasses
    }
    
    /** The base config data to convert to json, subclassed add their own properties */
    public function configData() {
        return [
            'type' => $this->ClassName
        ];
    }
}
