<?php

/** ... */
class SurveyMapComponent extends MapComponent {
    
    private static $has_one = [
        'Survey' => 'Survey'
    ];
    
    public function addExtraFields(FieldList $fields) {
        
        $fields->addFieldsToTab('Root.Main', [
            DropdownField::create(
                'SurveyID',
                'Survey',
                Survey::get()->map()->toArray()
            )
        ]);
    }
    
    
    public function configData() {
        
        // Start with the base config
        $data = parent::configData();
        
        
        // Add the survey id to the config
        $data += [
            'surveyID' => $this->SurveyID
        ];
        
        
        // Find a geo-point question to pass along
        $questions = $this->Survey()->Questions();
        foreach ($questions as $question) {
            
            if ($question->ClassName == "GeoQuestion" && $question->GeoType == "POINT") {
                $data["geoPointQuestion"] = $question->Handle;
                break;
            }
        }
        
        
        return $data;
    }
    
}
