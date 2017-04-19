<?php

/** ... */
class GeoQuestion extends Question {
    
    public $HiddenQuestion = true;
    
    private static $db = [
        "GeoType" => "Enum(array('POINT', 'LINE'), 'POINT')",
        "DataType" => "Int"
    ];
    
    public function extraFields() {
        return [
            DropdownField::create("GeoType", "Geometry Type",
                singleton('GeoQuestion')->dbObject('GeoType')->enumValues()
            ),
            NumericField::create("DataType", "Data Type")
                ->setDescription("The type of geometric data, how the geo api will reference this type")
        ];
    }
    
    
    public function validateValue($value) {
        
        $errors = [];
        
        // For Points, check for x & y pos
        if ($this->GeoType == "POINT") {
            
            if (!isset($value["x"])) {
                $errors[] = "Please provide an 'x' for '{$this->Handle}'";
            }
            else if (!is_numeric($value["x"])) {
                $errors[] = "'x' must be a number for '{$this->Handle}'";
            }
            
            if (!isset($value["y"])) {
                $errors[] = "Please provide an 'y' for '{$this->Handle}'";
            }
            else if (!is_numeric($value["y"])) {
                $errors[] = "'y' must be a number for '{$this->Handle}'";
            }
        }
        
        // if $this->Type == "LINE"
        // -> Check for x & y array
        
        // ...
        
        return $errors;
    }
    
    public function packValue($value) {
        
        // Create GeoRef & link to response
        $ref = GeoRef::makeRef($this->GeoType, $this->DataType, $value);
        // Return GeoRef id
        return $ref->ID;
    }
    
    public function unpackValue($value) {
        
        // Get GeoRef from id value
        $ref = GeoRef::get()->byID($value);
        
        
        // TODO: Handle error!
        if ($ref == null) { return null; }
        
        
        // Let the geo ref fetch its value from the geo api
        return $ref->fetchValue();
    }
    
}