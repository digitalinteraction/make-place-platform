<?php
/*
 *  A set of questions to ask a user
 *  To be data-driven and re-usable
 */
class Survey extends DataObject {
    
    private static $db = [
        "Name" => "Varchar(255)",
        "Handle" => "Varchar(255)",
        "SubmitTitle" => "Varchar(255)",
        "AuthType" => 'Enum(array("Member","None"), "Member")'
    ];
    
    private static $has_many = [
        "Questions" => "Question"
    ];
    
    private static $many_many = [
        "Geometries" => "GeoRef"
    ];
    
    private static $defaults = [
        "SubmitTitle" => "Submit"
    ];
    
    
    protected $securityToken = null;
    public $RedirectBack = false;
    
    
    
    function __construct($record = null, $isSingleton = false, $model = null) {
        
        parent::__construct($record, $isSingleton, $model);
        
        $this->toggleSecurity(true);
    }
    
    
    /** Event handler called before writing to the database */
    public function onBeforeWrite() {
        parent::onBeforeWrite();
        
        if ($this->Handle == null) {
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
        }
        
        // More checks ...
    }
    
    /** Generate the fields to edit a Survey */
    public function getCMSFields() {
        
        // Create a list of fields
        $fields = FieldList::create([
            TabSet::create('Root', Tab::create('Main'))
        ]);
        
        
        // Add our fields
        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Name','Name'),
            ReadonlyField::create('Handle', 'Handle'),
            TextField::create('SubmitTitle','Submit Title'),
            DropdownField::create('AuthType', 'Authentication',
                singleton('Survey')->dbObject('AuthType')->enumValues()
            ),
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
    
    
    
    
    public function getSurveyUrl() {
        return "/s/{$this->ID}/submit";
    }
    
    
    
    public function generateData($fields) {
        
        return [
            'SurveyID' => $this->ID,
            $this->getSecurityToken()->getName() => $this->getSecurityToken()->getValue(),
            'Fields' => $fields
        ];
    }
    
    
    
    public function getQuestionMap() {
        
        // Get all questions
        $questions = $this->Questions();
        $map = [];
        
        foreach ($questions as $question) {
            $map[$question->Handle] = $question;
        }
        
        return $map;
    }
    
    
    
    
    
    public function forTemplate() {
        
        return $this->renderWith("Survey");
    }
    
    public function WithRedirect() {
        $this->RedirectBack = true;
        return $this;
    }
    
    
    
    
    
    public function getSecurityTokenName() {
        return $this->getSecurityToken()->getName();
    }
    
    public function getSecurityTokenValue() {
        return $this->getSecurityToken()->getValue();
    }
}
