<?php

/** An extension to provide api authentication */
class ApiAuthExtension extends Extension {
    
    public function checkApiAuth() {
        
        // Get session from get var
        $apikey = $this->owner->request->getVar("apikey");
        
        // If not set, fail
        if ($apikey == null) { return false; }
        
        // Find the first ApiKey with that value
        $key = ApiKey::get()->filter([
            "Key" => $apikey,
            "Active" => true
        ])->first();
        
        // If null, fail
        if ($key == null) { return false; }
        
        // Fetch the owner of the apikey
        $member = $key->Member();
        
        // If there isn't a member, fail
        if ($member == null) { return false; }
        
        // Log them in
        $member->logIn();
        
        // Pass
        return true;
    }
}
