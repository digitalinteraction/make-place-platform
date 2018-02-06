<?php

/** Adds a filter for the survey responses to show */
class SurveyFilterMapComponent extends MapComponent {
    
    private static $db = [
        'Label' => 'Varchar(255)'
    ];
    
    public function addExtraFields(FieldList $fields) {
        
        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Label', 'Label')
                ->setDescription('How the filter will be presented to the user')
        ]);
    }
    
    public function customiseJson($json) {
        $json = parent::customiseJson($json);
        
        $json['surveys'] = Survey::get()->map('Name', 'ID')->toArray();
        
        return $json;
    }
}
