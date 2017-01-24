<?php
/*
 *  A set of questions to ask a user
 *  To be data-driven and re-usable
 */
class Survey extends DataObject {
    
    private static $db = [
        "Name" => "Varchar(255)",
        "Handle" => "Varchar(255)"
    ];
    
    private static $has_many = [
        "Questions" => "Question"
    ];
    
    
    /** Event handler called before writing to the database */
    public function onBeforeWrite() {
        parent::onBeforeWrite();
        
        $filter = URLSegmentFilter::create();
		$this->Handle = $filter->filter($this->Name);
    }
    
    
    public function getCMSFields() {
        
        // Get our parents fields
        $fields = parent::getCMSFields();
        
        
        // Remove the default
        $fields->removeByName(['Name', 'Handle', 'Questions']);
        
        
        
        // Add our fields in a better order
        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Name','Name'),
            ReadonlyField::create('Handle', 'Handle'),
            GridField::create(
                'Questions',
                'Questions',
                $this->Questions(),
                GridFieldConfig_RelationEditor::create()
                    ->removeComponentsByType('GridFieldAddExistingAutocompleter')
            )
        ]);
        
        
        return $fields;
    }
}
