<?php

/** ... */
class SurveyMapComponent extends MapComponent {
    
    private static $db = [
        'ActionColour' => 'Enum(array("blue", "green", "orange", "purple", "red"), "green")',
        'ActionMessage' => 'Varchar(255)',
        'PinColour' => 'Enum(array("blue", "green", "orange", "purple", "red"), "blue")'
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
            'pinColour' => $this->PinColour
        ];
        
        
        // Find a geo-point question to pass along
        $questions = $this->Survey()->Questions();
        foreach ($questions as $question) {
            
            if ($question->ClassName == "GeoQuestion" && $question->GeoType == "POINT") {
                $data["geoPointQuestion"] = $question->Handle;
                break;
            }
        }
        
        
        // Add auth config to the component
        $data["canView"] = Member::currentUserID() != null
            || $survey->ViewAuth == "None";
        $data["canSubmit"] = Member::currentUserID() != null
            || $survey->SubmitAuth == "None";
        
        
        return $data;
    }
    
}
