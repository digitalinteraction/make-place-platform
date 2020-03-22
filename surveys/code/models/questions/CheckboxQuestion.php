<?php
/** A survey question that presents a checkbox to the user */
class CheckboxQuestion extends Question {
    
    private static $db = [
        "Options" => "Varchar(255)"
    ];
    
    
    
    public function extraFields() {
        
        return [
            TextField::create("Options", "Options")
                ->setAttribute("placeholder", "A, B, C")
        ];
    }
    
    
    
    // Utils
    
    /** Gets the options as an array */
    public function getRawOptions() {
        return array_map('trim', explode(",", $this->Options));
    }
    
    /** Gets the options as a key-valued array */
    public function getOptionsArray() {
        
        $filter = URLSegmentFilter::create();
        $options = ArrayList::create();
        
        foreach(explode(",", $this->Options) as $option) {
            
            $options->push(ArrayData::create([
                "key" => trim($option),
                "value" => $filter->filter($option)
            ]));
            
        }
        
        return $options;
    }
    
    public function renderResponse($value) {
        // get the unpacked value
        $unpacked = $this->unpackValue($value);
        
        // Create a map of non-handlified names
        $mapped = [];
        
        // Create a filter-er to map each option to a value
        $filter = URLSegmentFilter::create();
        
        // Loop through the options
        foreach ($this->getRawOptions() as $option) {
            $slug = $filter->filter($option);

            if (in_array($slug, $unpacked)) {
                array_push($mapped, $option);
            }
        }
        
        // Join the matched strings together with commas
        return implode(", ", $mapped);
    }
    
    public function jsonSerialize() {
        
        // Add options to out json output
        return array_merge(parent::jsonSerialize(), [
            "options" => $this->getOptionsArray()->toNestedArray()
        ]);
    }
    
    function packValue($value) {
        if (is_array($value)) {
            return implode(",", $value);
        }
        else {
            return $value;
        }
    }

    function unpackValue($value) {
        if (is_string($value)) {
            return explode(",", $value);
        } else {
            return $value;
        }
    }
}
