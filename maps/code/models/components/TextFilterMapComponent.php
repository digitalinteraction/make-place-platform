<?php

/** Adds a filter for a Text Question */
class TextFilterMapComponent extends MapComponent {
    
    private static $extensions = [ 'SurveyFilterExtension' ];
    
    public function addExtraFields(FieldList $fields) {
        
        $fields->addFieldsToTab('Root.Main', [
            $this->labelField(),
            $this->surveyField()
        ]);
        
        // If there is a survey, add survey-specific fields
        if ($this->SurveyID != null) {
            
            $fields->addFieldsToTab('Root.Main', [
                $this->questionField('TextQuestion')
            ]);
        }
    }
}
