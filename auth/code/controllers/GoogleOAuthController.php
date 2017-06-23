<?php

// TODO: Coming Soon

/** A controller to handle google OAuth */
class GoogleOAuthController extends ContentController {
    
    private static $allowed_actions = [
        "login",
        "callback",
        "deauth"
    ];
    
    public $google = null;
    
    
    public function init() {
        parent::init();
        
        $this->google = new Google_Client();
    }
    
    
    
    public function index() {
        return $this->httpError(404);
    }
    
    
    
    /*  TODO:
    public function login() {
        
        
        // Remember the back url to return to after auth
        $back = $this->request->getVar("BackURL");
        if ($back != null) {
            Session::set("OAuthBackURL", $back);
        }
        
        
        // ...
        return "Login ...";
    }
    
    
    public function callback() {
        
        // ...
        return "callback ...";
    }
    
    public function deauth() {
        
        // ...
        return "deauth ...";
    }
    */
}
