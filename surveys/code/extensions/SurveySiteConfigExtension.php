<?php



/**
 * Adds survey properties to the SiteConfig
 * NOTE: Not worth getting the dynamic routing working now
 *
 * @codeCoverageIgnore
 */
class SurveySiteConfigExtension extends DataExtension {
    
    private static $db = [
        'SurveySegment' => 'Varchar(255)'
    ];
    
    
    /** Add our fields to the form */
    public function updateCMSFields(FieldList $fields) {
        
        $fields->addFieldsToTab('Root.Survey', [
            TextField::create('SurveySegment', 'SurveySegment')
        ]);
        
        return $fields;
    }
    
    
    /** Called before writting to the database */
    public function onBeforeWrite() {
        
        parent::onBeforeWrite();
        
        // Handle-ise the Survey Segment
        $this->owner->SurveySegment = URLSegmentFilter::create()->filter($this->owner->SurveySegment);
    }
    
    /** Called after writing to the database */
    public function onAfterWrite() {
        
        // NOTE: Doesn't persist, needs to called on init?
        $this->registerRoutes();
    }
    
    public function populateDefaults() {
        
        // Set the default segment
        
        if ($this->owner->SurveySegment == null) {
            $this->owner->SurveySegment = 'surveys';
        }
    }
    
    public function registerRoutes() {
        
        $value = $this->owner->SurveySegment;
        
        
        Director::addRules(100, [
            "$value/\$SurveyID" => 'SurveyController'
        ]);
    }
    
}
