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
    
    public function getType() {
        return "file";
    }
    
    public function getFieldName() {
        return "{$this->Handle}";
    }
    
    
    /* Rendering */
    // ...
    
    
    
    
    /* Value Management */
    public function validateValue($value) {
        
        // If an id was passed, validate that
        if (is_numeric($value)) {
            if (SurveyMedia::get()->byID($value) == null) {
                return [ "Invalid Media id for {$this->Handle}" ];
            }
            else {
                return [];
            }
        }
        
        // Check we have the properties
        $errors = [];
        if (!isset($value['name'])) {
            $errors[] = "Please provide 'name' for {$this->Handle}";
        }
        if (!isset($value['type'])) {
            $errors[] = "Please provide 'type' for {$this->Handle}";
        }
        if (!isset($value['tmp_name'])) {
            $errors[] = "Please provide 'tmp_name' for {$this->Handle}";
        }
        if (!isset($value['size'])) {
            $errors[] = "Please provide 'size' for {$this->Handle}";
        }
        
        return $errors;
    }
    
    public function packValue($value) {
        
        // If an id was passed, return the id to pack into json
        if (is_numeric($value)) { return $value; }
        
        // Use our stratey
        return MediaStrategy::get($this->Strategy)->createMedia($value);
    }
    
    public function unpackValue($value) {
        
        $media = SurveyMedia::get()->byID($value);
        
        // TODO: actually unpack the value
        return [ 'ID' => $media->ID ];
    }
    
    public function responseCreated($response, $value) {
        
        // Add the media as an sql relation to our response
        $media = SurveyMedia::get()->byID($value);
        $response->Media()->add($media);
    }
}
