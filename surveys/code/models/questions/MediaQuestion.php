<?php

/** A Survey question asking for some media */
class MediaQuestion extends Question {
    
    private static $db = [
        "MediaType" => 'Enum(array("ANY", "AUDIO", "IMAGE", "VIDEO"), "ANY")',
        "Strategy" => 'Enum(array("LOCAL", "S3"), "LOCAL")'
    ];
    
    
    
    /* CMS Fields */
    public function extraFields() {
        
        return [
            DropdownField::create("MediaType", "Media Type",
                singleton('MediaQuestion')->dbObject('MediaType')->enumValues()
            ),
            DropdownField::create("Strategy", "File Strategy",
                singleton('MediaQuestion')->dbObject('Strategy')->enumValues()
            )
        ];
    }
    
    
    /* Rendering */
    // ...
    
    
    
    
    /* Value Management */
    public function validateValue($value) {
        
    }
    
    public function packValue($value) {
        
    }
    
    public function unpackValue($value) {
        
    }
}
