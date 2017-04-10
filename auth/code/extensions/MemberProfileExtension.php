<?php

/** ... */
class MemberProfileExtension extends DataExtension {
    
    private static $db = [];
    
    private static $has_one = [
        'ProfileImage' => 'Image'
    ];
    
    public function getProfileImageUrl() {
        
        $image = $this->owner->ProfileImage();
        
        if ($image) {
            return $image->getURL();
        }
        else {
            return "/auth/images/default-profile.png";
        }
    }
}
