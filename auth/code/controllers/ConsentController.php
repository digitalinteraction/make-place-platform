<?php

/** A controller for handling user consent */
class ConsentController extends ContentController {
  
    protected $ClassName = "ConsentPage";
    protected $Title = "Consent";
    
    private static $allowed_actions = array(
        "ConsentForm"
    );
    
    public function index() {
        
        $user = Member::currentUser();
        if ($user == null || !$user->getHasVerified()) {
            return $this->redirect($this->getBackURL());
        }
        
        return $this;
    }
    
    public function Layout() {
        return $this->renderWith("ConsentPage");
    }
    
    public function Link($action = null) {
        return Controller::join_links('consent/', $action);
    }
    
    
    
    
    public function getBackURL() {
        
        // Get the url from the get var
        $url = $this->request->getVar("BackURL");
        
        // If not passed or point off-site, return home
        if (!$url || !Director::is_relative_url($url)) return "home/";
        
        return $url;
    }
    
    
    
    public function ConsentForm() {
        
        $fields = FieldList::create(array());
        
        $actions = FieldList::create(array(
            FormAction::create('declineConsent', 'I don\'t consent')
                ->setAttribute('class', 'red'),
            FormAction::create('acceptConsent', 'I consent')
                ->setAttribute('class', 'green')
        ));
        
        return Form::create($this, 'ConsentForm', $fields, $actions);
    }
    
    public function getAdminLink() {
        $email = 'openlab-admin@ncl.ac.uk';
        return "<a href='mailto:$email'>$email</a>";
    }
    
    public function getTermsLink() {
        return Director::absoluteURL('terms');
    }
    
    public function getPrivacyLink() {
        return Director::absoluteURL('privacy');
    }
    
    
    
    public function acceptConsent(array $data, Form $form) {
        $this->setConsent('Accept');
        return $this->redirect('/');
    }
    
    public function declineConsent(array $data, Form $form) {
        $this->setConsent('Reject');
        return $this->redirectBack();
    }
    
    public function setConsent($status) {
        $member = Member::currentUser();
        $member->ConsentStatus = $status;
        $member->ConsentUpdated = date('Y-m-d');
        $member->write();
    }
}
