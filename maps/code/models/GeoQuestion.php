<?php

/** A Question that asks about a geometry */
class GeoQuestion extends Question {
    
    public $HiddenQuestion = true;
    
    private static $db = [
        "GeoType" => "Enum(array('POINT', 'LINESTRING'), 'POINT')",
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
        
        // Let the parent find errors first
        $errors = parent::validateValue($value);
        if (count($errors)) { return $errors; }
        
        // If not required and no value, don't bother validating
        if (!$value && $this->Required == false) { return []; }
        
        
        
        // If the value is a number, check it is a valid georef
        if (is_numeric($value)) {
            if (GeoRef::get()->byID($value) == null) {
                return "Invalid geo id for {$this->Handle}";
            }
            else {
                return [];
            }
        }
        
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
        
        // if $this->Type == "LINESTRING"
        // -> Check for x & y array
        if ($this->GeoType == "LINESTRING") {
            
            // Make sure its an array
            if (!isset($value['points']) || !is_array($value['points'])) {
                $errors[] = "Please provide 'points' for '{$this->Handle}' as an array";
            }
            else if (count($value['points']) < 2) {
                $errors[] = "A LINESTRING needs at least 2 points";
            }
            else {
                
                // Make sure each elem has an x & y
                foreach ($value['points'] as $i => $point) {
                    if (!$this->validPoint($point)) {
                        $errors[] = "{$this->Handle}[$i] must have an x & y coord";
                    }
                }
                
            }
            
            
        }
        
        // ...
        
        return $errors;
    }
    
    public function packValue($value) {
        
        // If its a number, its already 'packed'
        if (is_numeric($value)) { return $value; }
        
        // Create GeoRef to link to SurveyResponse by returning its id
        if ($value) {
            $ref = GeoRef::makeRef($this->GeoType, $this->DataType, $value);
            return $ref != null ? $ref->ID : null;
        }
        
        return null;
    }
    
    public function unpackValue($value) {
        
        if ($value == null) { return $value; }
        
        // Get GeoRef from id value
        $ref = GeoRef::get()->byID($value);
        
        
        // TODO: Handle error!
        if ($ref == null) { return null; }
        
        
        // Let the geo ref fetch its value from the geo api
        return $ref->fetchValue();
    }
    
    public function responseCreated($response, $value) {
        
        // Add the geometry as an sql relation to our response
        if ($value) {
            $ref = GeoRef::get()->byID($value);
            $response->Geometries()->add($ref);
        }
    }
    
    
    
    private function validPoint($point) {
        return isset($point['x']) && isset($point['y']) && is_numeric($point['x']) && is_numeric($point['y']);
    }
    
}
