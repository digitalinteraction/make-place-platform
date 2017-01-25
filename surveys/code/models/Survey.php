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
    
    
    protected $securityToken = null;
    
    
    
    function __construct($record = null, $isSingleton = false, $model = null) {
        
        parent::__construct($record, $isSingleton, $model);
        
        $this->toggleSecurity(true);
    }
    
    
    /** Event handler called before writing to the database */
    public function onBeforeWrite() {
        parent::onBeforeWrite();
        
        $otherHandles = Survey::get()->column('Handle');
        
        $extra = "";
        $count = 1;
        $filter = URLSegmentFilter::create();
        
        do {
            
            $this->Handle = $filter->filter($this->Name . $extra);
            $count++;
            $extra = "-$count";
            
        }
        while (in_array($this->Handle, $otherHandles));
        
        // More checks ...
    }
    
    /** Generate the fields to edit a Survey */
    public function getCMSFields() {
        
        // Get our parents fields
        $fields = parent::getCMSFields();
        
        
        // Remove the default values
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
    
    
    
    
    public function toggleSecurity($secure) {
        $this->securityToken = ($secure) ? new SecurityToken() : new NullSecurityToken();
    }
    
    public function getSecurityToken() {
        return $this->securityToken;
    }
    
    
    
    
    public function surveyUrl() {
        
        return "/s/$ID/submit";
    }
    
    
    
    public function generateData($fields) {
        
        return [
            'SurveyID' => $this->ID,
            $this->getSecurityToken()->getName() => $this->getSecurityToken()->getValue(),
            'Fields' => $fields
        ];
    }
    
    
    
    
    
    public function forTemplate() {
        
        return $this->renderWith("Survey");
    }
}
