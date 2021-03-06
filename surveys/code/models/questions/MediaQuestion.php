<?php

/** A Survey question asking for some media */
class MediaQuestion extends Question {
    
    private static $db = [
        "MediaType" => 'Enum(array("ANY", "AUDIO", "IMAGE", "VIDEO"), "ANY")',
        "Strategy" => 'Enum(array("LOCAL", "S3"), "LOCAL")'
    ];
    
    
    
    /* CMS Fields */
    public function extraFields() {
        
        // NOTE: Don't add extra fields for now, they don't do anything at the moment
        
        return [
            // DropdownField::create("MediaType", "Media Type",
            //     singleton('MediaQuestion')->dbObject('MediaType')->enumValues()
            // ),
            // DropdownField::create("Strategy", "File Strategy",
            //     singleton('MediaQuestion')->dbObject('Strategy')->enumValues()
            // )
        ];
    }
    
    public function getType() { return "file"; }
    
    public function getFieldName() { return "{$this->Handle}"; }
    
    
    // Rendering
    
    /** Renders an response to this question */
    public function renderResponse($value) {
        
        $media = SurveyMedia::get()->byID($value);
        
        $url = MediaStrategy::get($this->Strategy)->mediaUrl($media);
        
        $type = substr($media->Type, 0, 5);
        
        $inner = "";
        
        if ($type == "image") {
            $inner = "<img src='$url' alt='{$media->Name}'>";
        }
        else if ($type == "audio") {
            $inner = "<audio controls> <source src='$url' type='{$media->Type}'> </audio>";
        }
        else if ($type == "video") {
            $inner = "<video webkit-playsinline playsinline controls> <source src='$url' type='{$media->Type}'> </video>";
        }
        else {
            $inner = "Unknown file was uploaded";
            $type = "unknown";
        }
        
        return "<span class='media $type'>$inner</span>";
    }
    
    
    
    /* Value Management */
    public function validateValue($value) {
        
        // Let the parent validate first
        $errors = parent::validateValue($value);
        if (count($errors)) return $errors;
        
        
        // If not set and not required, don't validate
        if (!$value && $this->Required == false) { return []; }
        
        
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
        
        // Use our stratey to create a SurveyMedia & return its id
        if ($value) {
            return MediaStrategy::get($this->Strategy)->createMedia($value);
        }
        
        return null;
    }
    
    public function unpackValue($value) {
        
        // If no value, do nothing
        if ($value == null) { return $value; }
        
        // Get the value of the media
        $media = SurveyMedia::get()->byID($value);
        
        // TODO: actually unpack the value
        return [ 'id' => $media->ID, ];
    }
    
    public function responseCreated($response, $value) {
        
        // Add the media as an sql relation to our response
        if ($value) {
            $media = SurveyMedia::get()->byID($value);
            $response->Media()->add($media);
        }
    }
}
