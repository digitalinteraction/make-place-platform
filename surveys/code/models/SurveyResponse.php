<?php

/** ... */
class SurveyResponse extends DataObject {
    
    private static $db = [
        "Responses" => "JsonText",
        'Latitude' => 'Decimal(16, 8)',
        'Longitude' => 'Decimal(16, 8)',
    ];
    
    private static $has_one = [
        "Member" => "Member",
        "Survey" => "Survey"
    ];
    
    
    public function getValues() {
        
        $responses = $this->jsonField('Responses');
        
        $values = ArrayList::create();
        
        $questions = $this->Survey()->Questions();
        
        foreach ($questions as $question) {
            
            $key = $question->Handle;
            
            if (isset($responses[$key])) {
                
                $value = $responses[$key];
                
                $values->push(ArrayData::create([
                    "Question" => $question,
                    "Key" => $key,
                    "Value" => $value,
                    "Rendered" => $question->renderResponse($value)
                ]));
            }
        }
        
        return $values;
    }
    
    
    public function getTitle() {
        
        return $this->Member()->getName() . "'s Response";
    }
    
    public function toJson() {
        
        $rawValues = $this->jsonField('Responses');
        $values = [];
        
        $questions = $this->Survey()->Questions();
        
        foreach ($questions as $question) {
            $values[] = [
                "key" => $question->Name,
                "value" => isset($rawValues[$question->Handle]) ? $rawValues[$question->Handle] : ''
            ];
        }
        
        return [
            'id' => $this->ID,
            'surveyId' => $this->SurveyID,
            'memberId' => $this->MemberID,
            'lat' => floatval($this->Latitude),
            'lng' => floatval($this->Longitude),
            'values' => $values
        ];
    }
}
