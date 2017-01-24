<?php

/** Adds survey properties to the SiteConfig */
class SurveySiteConfigExtension extends DataExtension {
    
    private static $db = [
        // 'SurveySegment' => 'Varchar(255)'
    ];
    
    
    /** Add our fields to the form */
    public function updateCMSFields(FieldList $fields) {
        
        // $fields->addFieldsToTab('Root.Survey', [
        //     TextField::create('SurveySegment', 'SurveySegment')
        // ]);
        
        return $fields;
    }
    
    
    /** Called before writting to the database */
    public function onBeforeWrite() {
        
        parent::onBeforeWrite();
        
        // Handle-ise the Survey Segment
        // $this->owner->SurveySegment = URLSegmentFilter::create()->filter($this->owner->SurveySegment);
    }
    
    public function populateDefaults() {
        
        // Set the default segment
        // $this->owner->SurveySegment = 'surveys';
    }
    
}
