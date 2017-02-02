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
        
        $data = parent::configData();
        
        $data += [
            'surveyID' => $this->SurveyID
        ];
        
        return $data;
    }
    
}
