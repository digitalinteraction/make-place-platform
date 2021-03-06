<?php
/* A set of questions to ask a user, configurable through the cms */
class Survey extends DataObject {
    
    private static $extensions = [ 'PermsFieldExtension' ];
    
    private static $db = [
        "Name" => "Varchar(255)",
        "Description" => "HTMLText",
        "Handle" => "Varchar(255)",
        "SubmitTitle" => "Varchar(255)",
        'Active' => 'Boolean',
        
        'ResponseViewPerms' => 'Enum(array("Anyone","Member","NoOne","Group"), "Member")',
        'ResponseMakePerms' => 'Enum(array("Member","NoOne","Group"), "Member")'
    ];
    
    private static $has_many = [
        "Questions" => "Question",
        "Responses" => "SurveyResponse"
    ];
    
    private static $many_many = [
        'ResponseViewGroups' => 'Group',
        'ResponseMakeGroups' => 'Group'
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
        
        
        // Listboxfield values are escaped, use ASCII char instead of &raquo;
        $groupsMap = array();
        foreach(Group::get() as $group) {
            $groupsMap[$group->ID] = $group->getBreadcrumbs(' > ');
        }
        asort($groupsMap);
        
        
        // Get permission maps
        $groupsMap = $this->groupsMap();
        $viewPerms = $this->viewPerms();
        $makePerms = $this->makePerms();
        
        
        // A config for sortable questions
        $questionConfig = GridFieldConfig_RelationEditor::create()
            ->removeComponentsByType('GridFieldAddExistingAutocompleter')
            ->removeComponentsByType('GridFieldExportButton')
            ->removeComponentsByType('GridFieldPrintButton')
            ->removeComponentsByType('GridFieldDeleteAction')
            ->removeComponentsByType('GridFieldFilterHeader')
            ->addComponent(new GridFieldSortableRows('Order'))
            ->addComponent(new GridFieldDeleteAction());
        
        $responseConfig = GridFieldConfig_RelationEditor::create()
            ->removeComponentsByType('GridFieldAddExistingAutocompleter')
            ->removeComponentsByType('GridFieldExportButton')
            ->removeComponentsByType('GridFieldPrintButton')
            ->removeComponentsByType('GridFieldDeleteAction')
            ->removeComponentsByType('GridFieldAddNewButton')
            ->removeComponentsByType('GridFieldFilterHeader');
        
        
        // 'ResponseViewPerms' => 'Enum(array("Anyone","Member","NoOne","Group"), "Member")',
        // 'ResponseMakePerms' => 'Enum(array("Member","NoOne","Group"), "Member")'
        
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
            DropdownField::create('ResponseViewPerms', 'Who can view responses', $viewPerms),
            $viewGroups = ListboxField::create('ResponseViewGroups', 'Groups', $groupsMap)->setMultiple(true),
            DropdownField::create('ResponseMakePerms', 'Who can respond', $makePerms),
            $makeGroups = ListboxField::create('ResponseMakeGroups', 'Groups', $groupsMap)->setMultiple(true),
            
            HeaderField::Create("DescHeading", "Description"),
            HtmlEditorField::create('Description', 'Description')
                ->setDescription('A bit of content to display above a survey when it is being responded to')
        ]);
        
        $viewGroups->displayIf('ResponseViewPerms')->isEqualTo('Group');
        $makeGroups->displayIf('ResponseMakePerms')->isEqualTo('Group');
        
        if ($this->ID && $this->Active == false) {
            $fields->addFieldsToTab('Root.Questions', [
                GridField::create(
                    'Questions',
                    'Questions',
                    $this->Questions(),
                    $questionConfig
                )
            ]);
            
            $fields->addFieldsToTab('Root.Responses', [
                GridField::create(
                    'Responses',
                    'Responses',
                    $this->Responses(),
                    $responseConfig
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
