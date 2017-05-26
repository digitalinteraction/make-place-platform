<?php
/**
 *
 */
class DropdownQuestion extends Question {
    
    private static $db = [
        "Options" => "Varchar(255)"
    ];
    
    
    
    public function extraFields() {
        
        return [
            TextField::create("Options", "Options")
                ->setAttribute("placeholder", "A, B, C")
        ];
    }
    
    
    public function getRawOptions() {
    
        return array_map('trim', explode(",", $this->Options));
    }
    
    public function getOptionsArray() {
        
        $options = [];
        
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
        
        // Get the options array (as values)
        $options = $this->getRawOptions();
        
        // Create a filter-er to map each option to a value
        $filter = URLSegmentFilter::create();
        
        
        // Loop through the options
        foreach ($options as $option) {
            
            // Handle-ify the option to check it against the passed value
            if ($filter->filter($option) == $value) {
                return $option;
            }
        }
        
        // If we reached here it wasn't found
        return null;
    }
    
    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(), [
            "options" => $this->getOptionsArray()->toNestedArray()
        ]);
    }
    
}
