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
    
    
    
    public function getOptionsArray() {
        
        $options = [];
        
        $filter = URLSegmentFilter::create();
        
        $options = ArrayList::create();
        
        foreach(explode(",", $this->Options) as $option) {
            
            $options->push(ArrayData::create([
                "Key" => $option,
                "Value" => $filter->filter($option)
            ]));
            
        }
        
        return $options;
    }
}
