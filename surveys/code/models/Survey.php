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
        "ViewAuth" => 'Enum(array("Member","None"), "None")',
        "SubmitAuth" => 'Enum(array("Member","None"), "Member")',
        'Active' => 'Boolean'
    ];
    
    private static $has_many = [
        "Questions" => "Question"
    ];
    
    private static $defaults = [
        "SubmitTitle" => "Submit"
    ];
    
    private static $summary_fields = [
        'Name' => 'Name',
        'Active' => 'Active',
        'Handle' => 'Handle',
        'Questions.Count' => 'Num Questions'
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
        
        
        // A config for sortable questions
        $config = GridFieldConfig_RelationEditor::create()
            ->removeComponentsByType('GridFieldAddExistingAutocompleter')
            ->removeComponentsByType('GridFieldExportButton')
            ->removeComponentsByType('GridFieldPrintButton')
            ->removeComponentsByType('GridFieldDeleteAction')
            ->removeComponentsByType('GridFieldFilterHeader')
            ->addComponent(new GridFieldSortableRows('Order'))
            ->addComponent(new GridFieldDeleteAction());
        
        
        $viewAuth = singleton('Survey')->dbObject('ViewAuth')->enumValues();
        $submitAuth = singleton('Survey')->dbObject('SubmitAuth')->enumValues();
        
        // Add our fields
        $fields->addFieldsToTab('Root.Main', [
            HeaderField::Create("GeneralHeading", "Survey Info"),
            TextField::create('Name','Name'),
            ReadonlyField::create('Handle', 'Handle'),
            TextField::create('SubmitTitle','Submit action')
                ->setDescription("The title of the button to respond to this survey"),
            CheckboxField::create('Active', 'If the survey can be responded to')
                ->setDescription("NOTE: Once active questions cannot be changed"),
            
            HeaderField::Create("PermsHeading", "Permissions"),
            DropdownField::create('ViewAuth', 'View Permissions', $viewAuth)
                ->setDescription("The permission needed to view responses to this survey"),
            DropdownField::create('SubmitAuth', 'Permission to respond', $submitAuth )
                ->setDescription("The permission needed to respond to this survey")
        ]);
        
        if ($this->ID && $this->Active == false) {
            $fields->addFieldsToTab('Root.Questions', [
                GridField::create(
                    'Questions',
                    'Questions',
                    $this->Questions(),
                    $config
                )
            ]);
        }
        
        
        return $fields;
    }
    
    
    
    
    public function toggleSecurity($secure) {
        $this->securityToken = ($secure) ? new SecurityToken() : new NullSecurityToken();
    }
    
    public function getSecurityToken() {
        return $this->securityToken;
    }
    
    
    
    
    public function getSurveyUrl() {
        return "/api/survey/{$this->ID}/submit";
    }
    
    
    
    public function generateData($fields) {
        
        return $this->generateFormData([
            'fields' => $fields
        ]);
    }
    
    public function generateFormData($data) {
        
        $data[$this->getSecurityToken()->getName()] = $this->getSecurityToken()->getValue();
        return $data;
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
    
    
    
    public function Questions() {
      return parent::Questions()->sort('Order');
    }
}
