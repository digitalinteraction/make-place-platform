<?php

/** An extension for Member adding profile information */
class MemberProfileExtension extends DataExtension {
    
    private static $db = [];
    
    private static $has_one = [
        'ProfileImage' => 'Image'
    ];
    
    public function getProfileImageUrl() {
        
        return "/auth/images/default-profile.png";
    }
    
    public function updateProfile($data) {
        
        $hasChange = false;
        $errors = [];
        
        // If FirstName is set, update that
        if (!isset($data["FirstName"]) || $data["FirstName"] == "") {
            $errors[] = 'FirstName';
        }
        else {
            $this->owner->FirstName = $data["FirstName"];
            $hasChange = true;
        }
        
        // If Surname is set, update that
        if (!isset($data["Surname"]) || $data["Surname"] == "") {
            $errors[] = 'Surname';
        }
        else {
            $this->owner->Surname = $data["Surname"];
            $hasChange = true;
        }
        
        /* Coming soon ...
        if (isset($data["ProfileImage"]) && $data["ProfileImage"]["tmp_name"] != "") {
            # code...
            
            // A file was uploaded
            // ...
            
            
            
            $allowedTypes = [
                "image/png", "image/jpg", "image/jpeg"
            ];
            
            
            if (!in_array($data["ProfileImage"]["type"], $allowedTypes)) {
                $errors[] = "Please upload a png or jpg image";
            }
            else {
                
                // Create a file
                // Properties: name, type, tmp_name, error, size
                
                // Find if the file exists
                // -> If so, remove the old file & delete the record
                
                // Find or create assets/profiles
                
                // Create a new file record with the image
                
                // Save the new file
                
                // $hasChange = true;
            }
            
        }*/
        
        if (count($errors) === 0 && $hasChange) {
            $this->owner->write();
        }
        
        
        return $errors;
    }
    
    // public function updateProfileImage($image, &$errors) {
    //
    //     // More magic happens here ...
    // }
}
