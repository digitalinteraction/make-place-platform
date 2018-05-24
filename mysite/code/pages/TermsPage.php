<?php

/** A page to show the terms & conditions */
class TermsPage extends Page {
    
    private static $defaults = [
        'ShowInMenus' => 0,
        'ShowInSearch' => 0
    ];
    
    public function getCMSFields() {
        
        $fields = parent::getCMSFields();
        $fields->removeByName('Content');
        return $fields;
    }
}

class TermsPage_Controller extends Page_Controller {
    
    public function getContactEmailLink() {
        $email = CONTACT_EMAIL;
        return '<a href="mailto:'.$email.'?subject=make.place">'.$email.'</a>';
    }
    
    public function getPrivacyLink() {
        $url = Director::absoluteURL('/privacy');
        return '<a href="'.$url.'">'.$url.'</a>';
    }
}
