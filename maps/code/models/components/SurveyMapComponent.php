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
        
        $fields->addFieldsToTab('Root.Main', [
            DropdownField::create(
                'SurveyID',
                'Survey',
                Survey::get()->map()->toArray()
            ),
            TextField::create('ActionMessage', 'Action'),
            TextField::create('PositionQuestion', 'Position Question'),
            TextField::create('HighlightQuestion', 'Highlight Question'),
            DropdownField::create('ActionColour', 'Action Colour',
                singleton('SurveyMapComponent')->dbObject('ActionColour')->enumValues()
            ),
            DropdownField::create('PinColour', 'Pin Colour',
                singleton('SurveyMapComponent')->dbObject('PinColour')->enumValues()
            )
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
