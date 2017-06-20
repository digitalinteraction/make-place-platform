<?php

/** ... */
class SurveyMapComponent extends MapComponent {
    
    private static $db = [
        'ActionColour' => 'Enum(array("blue", "green", "orange", "purple", "red"), "green")',
        'ActionMessage' => 'Varchar(255)',
        'PinColour' => 'Enum(array("blue", "green", "orange", "purple", "red"), "blue")',
        'PositionQuestion' => 'Varchar(255)',
        'HighlightQuestion' => 'Varchar(255)'
    ];
    
    private static $has_one = [
        'Survey' => 'Survey'
    ];
    
    private static $defaults = [
        'ActionMessage' => 'Add Response',
        'ActionColour' => 'green',
        'PinColour' => 'blue'
    ];
    
    public function addExtraFields(FieldList $fields) {
        
        $surveyList = Survey::get()->map()->toArray();
        $actionColours = singleton('SurveyMapComponent')->dbObject('ActionColour')->enumValues();
        $pinColours = singleton('SurveyMapComponent')->dbObject('PinColour')->enumValues();
        
        
        $fields->addFieldsToTab('Root.Main', [
          HeaderField::create("SurveyCompHeader", "Survey Component", 2),
            DropdownField::create( 'SurveyID', 'Survey', $surveyList)
                ->setDescription("The survey to add to the map")
        ]);
        
        if ($this->SurveyID == null) { return; }
        
        $geoQuestions = $this->Survey()->Questions()
            ->filter('ClassName', 'GeoQuestion')
            ->map('Handle', 'Name')
            ->toArray();
        $geoQuestions[''] = 'None';
        
        
        $fields->addFieldsToTab('Root.Survey.Geom', [
            DropdownField::create("PositionQuestion", 'Position Question', $geoQuestions)
                ->setDescription("The question responsible for the survey's location"),
            DropdownField::create("HighlightQuestion", 'Highlight Question', $geoQuestions)
                ->setDescription("The question to display extra geometries when selected"),
        ]);
        
        $fields->addFieldsToTab('Root.Survey.Appearance', [
            TextField::create('ActionMessage', 'Action')
                ->setDescription("The action to add a response from the map"),
            DropdownField::create('ActionColour', 'Action Colour', $actionColours)
                ->setDescription("The colour of the action on the map"),
            DropdownField::create('PinColour', 'Pin Colour', $pinColours)
                ->setDescription("The colour of the response pins")
        ]);
    }
    
    
    public function configData() {
        
        // Start with the base config
        $data = parent::configData();
        
        $survey = $this->Survey();
        
        
        // Add the survey id to the config
        $data += [
            'surveyID' => $survey->ID,
            'actionColour' => $this->ActionColour,
            'actionMessage' => $this->ActionMessage,
            'pinColour' => $this->PinColour,
            'positionQuestion' => $this->PositionQuestion
        ];
        
        if ($this->HighlightQuestion != null) {
            $data['highlightQuestion'] = $this->HighlightQuestion;
        }
        
        
        // Get the current member and check they're verified
        $member = Member::currentUser();
        $verified = $member ? $member->getHasVerified() : false;
        
        
        // Add auth config to the component
        $data["canView"] = $verified || $survey->ViewAuth == "None";
        $data["canSubmit"] = $verified || $survey->SubmitAuth == "None";
        
        
        return $data;
    }
    
}
