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
        "Placeholder" => "Varchar(255)"
    ];
    
    private static $has_one = [
        "Survey" => "Survey"
    ];
    
    
    protected $extraClasses = [];
    
    
    /** Event handler called before writing to the database */
    public function onBeforeWrite() {
        parent::onBeforeWrite();
        
        if ($this->Handle == null) {
            
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
            
        }
        
        // More checks ...
    }
    
    
    
    /* Fields */
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
            TextField::create('Placeholder', 'Placeholder'),
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
        $fields->addFieldsToTab('Root.Main', array_merge(
            [HeaderField::create('PropertiesLabel', 'Question Properties', 2)],
            $extraFields
        ));
        
        
        // Add fields depending on $this->Type
        // If Type changes, show an alery similar to Page's Type
        
        return $fields;
    }
    
    public function availableTypes() {
        
        return ClassUtils::getSubclasses('Question', 'Question', true);
    }
    
    public function extraFields() {
        
        return [];
    }
    
    public function sample() {
        return [
            "type" => $this->ClassName
        ];
    }
    
    
    
    /* Rendering */
    public function forTemplate() {
        
        return $this->renderWith("QuestionHolder");
    }
    
    public function renderField() {
        
        $templates = SSViewer::get_templates_by_class(get_class($this));
        
        if (count($templates) > 0) {
            return $this->renderWith($templates[0]);
        }
        else {
            return $this->renderWith("Question");
        }
    }
    
    public function renderResponse($value) {
        return $value;
    }
    
    
    /* Types */
    public function getType() {
        return "text";
    }
    
    public function getFieldName() {
        
        return "Fields[{$this->Handle}]";
    }
    
    public function getClasses() {
        
        $all =  array_merge(["control"], $this->extraClasses);
        
        return implode($all, " ");
    }
    
    
    
    /* Handling Values */
    /** Called to check if a value is acceptable to save in a SurveyResponse, return an array of errors */
    public function validateValue($value) {
        return [];
    }
    
    /** Called once to pack a value into a SurveyResponse's json */
    public function packValue($value) {
        return $value;
    }
    
    /** Called to unpack a value from a SurveyResponse's json */
    public function unpackValue($value) {
        return $value;
    }
    
    /** Called once a response is created, with the value returned from packValue */
    public function responseCreated($response, $value) {
        // Override in subclasses
    }
}
