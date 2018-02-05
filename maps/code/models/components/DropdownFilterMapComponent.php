<?php

/** Adds a filter for a Dropdown Question */
class DropdownFilterMapComponent extends MapComponent {
    
    private static $extensions = [ 'SurveyFilterExtension' ];
    
    public function addExtraFields(FieldList $fields) {
        
        $fields->addFieldsToTab('Root.Main', [
            $this->labelField(),
            $this->surveyField()
        ]);
        
        // If there is a survey, add survey-specific fields
        if ($this->SurveyID != null) {
            
            $fields->addFieldsToTab('Root.Main', [
                $this->questionField('DropdownQuestion')
            ]);
        }
    }
    
    public function customiseJson($json) {
        
        $json = parent::customiseJson($json);
        
        if ($this->QuestionID !== null) {
            $json['question'] = $this->Question()->jsonSerialize();
        }
        else {
            $json['dropdownOptions'] = null;
        }
        
        return $json;
    }
}
