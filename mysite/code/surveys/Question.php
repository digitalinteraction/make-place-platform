<?php
/*
 *
 */
class Question extends DataObject {
    
    private static $db = [
        "Name" => "Varchar(255)",
        "Handle" => "Varchar(255)",
        "Label" => "Varchar(255)",
        "Description" => "Varchar(255)",
        // "Type" => "Varchar(255)"
    ];
    
    private static $has_one = [
        "Survey" => "Survey"
    ];
    
    
    /** Event handler called before writing to the database */
    public function onBeforeWrite() {
        parent::onBeforeWrite();
        
        // We need to make sure the handle is unique for the form
        $extra = "";
        $count = 0;
        $filter = URLSegmentFilter::create();
        
        
        // Get the handles of the other questions on our surve
        if ($this->SurveyID != null) {
            $otherHandles = $this->Survey()->Questions()->column('Handle');
        }
        else {
            
            // If the survey isn't set, use an empty set
            $otherHandles = array();
        }
        
        
        // Keep generating handles until it is unique
        do {
            
            // Generate a handle
            $count++;
            $this->Handle = $filter->filter($this->Name . $extra);
            $extra = "-$count";
            
        } while( in_array($this->Handle, $otherHandles));
        
        // ...
    }
    
    
    public function getCMSFields() {
    
        $fields = parent::getCMSFields();
        
        $fields->removeByName(['Name', 'Handle', 'Label', 'Description', 'Type', 'SurveyID']);
        
        
        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Name', 'Name'),
            ReadonlyField::create('Handle', 'Handle'),
            DropdownField::create('Type', 'Type', $this->availableTypes())
        ]);
        
        
        // Add fields depending on $this->Type
        // If Type changes, show an alery similar to Page's Type
        
        return $fields;
    }
    
    public function availableTypes() {
        
        // Get our subclasses
        $subclasses = ClassInfo::subclassesFor(get_class());
        
        // For some reason it includes question too
        // Remove that
        // unset($subclasses['Question']);
        
        // Return just the values
        return array_values($subclasses);
    }
    
}
