<?php

/** A controller to render the current member's profile */
class ProfileController extends ContentController {
    
    public $Content = "<p> Profile Controller </p>";
    protected $Title = "Me";
    protected $ClassName = "ProfilePage";
    
    public function Link($actions = null) {
        return $this->join_links("me", $actions);
    }
    
    public function init() {
        
        parent::init();
        
        // 404 if there's no user
        if (Member::currentUserID() == null) {
            return $this->httpError(404);
        }
        
        $this->Member = Member::currentUser();
        
        // ...
    }
    
    public function Layout() {
        return $this->renderWith("ProfilePage");
    }
}
