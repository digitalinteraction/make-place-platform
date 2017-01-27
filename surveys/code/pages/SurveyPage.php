<?php

/** ... */
class SurveyPage extends Page {
    
    private static $has_one = [
        'Survey' => 'Survey'
    ];
    
    
    public function getCMSFields() {
        
        $fields = parent::getCMSFields();
        
        // var_dump(Survey::get()->map());
        
        $fields->addFieldsToTab('Root.Survey', [
            DropdownField::create(
                'SurveyID',
                'Survey',
                Survey::get()->map()->toArray()
            )
        ]);
        
        return $fields;
    }
}
