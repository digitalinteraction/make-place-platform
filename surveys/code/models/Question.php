<?php
/*
 *
 */
class Question extends DataObject {
    
    private static $db = [
        "Name" => "Varchar(255)",
        "Handle" => "Varchar(255)",
        "Label" => "Varchar(255)",
        "Description" => "Varchar(255)"
    ];
    
    private static $has_one = [
        "Survey" => "Survey"
    ];
    
    
    /** Event handler called before writing to the database */
    public function onBeforeWrite() {
        parent::onBeforeWrite();
        
        // We need to make sure the handle is unique for the form
        $extra = "";
        $count = 1;
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
            $this->Handle = $filter->filter($this->Name . $extra);
            $count++;
            $extra = "-$count";
            
        } while( in_array($this->Handle, $otherHandles));
        
        // More checks ...
    }
    
    
    public function getCMSFields() {
        
        // Create the fields ourself
        $fields = FieldList::create([
            TabSet::create('Root', Tab::create('Main'))
        ]);
        
        
        // Add the base fields
        $fields->addFieldsToTab('Root.Main', [
            HeaderField::create('InfoLabel', 'Question Info', 2),
            TextField::create('Name', 'Name'),
            ReadonlyField::create('Handle', 'Handle'),
            TextField::create('Label', 'Label'),
            TextareaField::create('Description', 'Description'),
            DropdownField::create('ClassName', 'Type', $this->availableTypes())
                ->setDescription("You will need to save for this property to update")
        ]);
        
        
        // If this model hasn't been created yet, don't add any more fields
        if ($this->ID == null) {
            return $fields;
        }
        
        // If the subclass doesn't add extra fields, don't add any more fields
        $extraFields = $this->extraFields();
        if (count($extraFields) == 0) {
            return $fields;
        }
        
        
        // If the subclass does add fields, add a header and the fields
        // $fields->addFieldsToTab('Root.Main', array_merge([
        //     HeaderField::create('Extra', 'Question Properties', 2)
        // ], $extraFields));
        
        $fields->addFieldsToTab('Root.Main', array_merge(
            [HeaderField::create('PropertiesLabel', 'Question Properties', 2)],
            $extraFields
        ));
        
        
        // Add fields depending on $this->Type
        // If Type changes, show an alery similar to Page's Type
        
        return $fields;
    }
    
    public function availableTypes() {
        
        // Get our subclasses
        $subclasses = ClassInfo::subclassesFor(get_class());
        
        // For some reason it includes question too
        // Remove that
        unset($subclasses['Question']);
        
        // Give them readable names
        // -> removes 'Question' from the end
        // -> adds spaces between words
        //    ref: http://stackoverflow.com/questions/1089613/php-put-a-space-in-front-of-capitals-in-a-string-regex
        foreach ($subclasses as $key => $value) {
            
            $nameName = str_replace("Question", "", $key);
            $subclasses[$key] = trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', $nameName));
        }
        
        // Return just the values
        return $subclasses;
    }
    
    public function extraFields() {
        
        return [];
    }
    
}
