<?php

/** An extension for members to add consent fields */
class ConsentMemberExtension extends DataExtension {
  
    private static $db = [
      'ConsentUpdated' => 'Date',
      'ConsentStatus' => 'Enum(array("Signup","Accept","Reject",),"Signup")'
    ];
    
    public function getHasConsent() {
        $consent = SiteConfig::current_site_config()->ConsentEffectiveDate;
        return strtotime($this->owner->ConsentUpdated) >= strtotime($consent)
            && $this->owner->ConsentStatus !== 'Reject';
    }
    
    public function updateCMSFields(FieldList $fields) {
        
        $fields->removeByName('ConsentUpdated');
        $fields->removeByName('ConsentStatus');
    }
}
