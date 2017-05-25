<?php

/** A response to the questions of a survey */
class SurveyResponse extends DataObject {
    
    private static $extensions = [
        "JsonFieldExtension", "CommentableDataExtension"
    ];
    
    public $HiddenQuestion = false;
    
    private static $db = [
        "Responses" => "JsonText"
    ];
    
    private static $has_one = [
        "Member" => "Member",
        "Survey" => "Survey"
    ];
    
    private static $many_many = [
        "Geometries" => "GeoRef",
        "Media" => "SurveyMedia"
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
            
            $value = isset($rawValues[$question->Handle]) ? $rawValues[$question->Handle] : '';
            
            $values[$question->Handle] = [
                "name" => $question->Name,
                "value" => $question->unpackValue($value)
            ];
        }
        
        return [
            'id' => $this->ID,
            'created' => $this->Created,
            'surveyId' => $this->SurveyID,
            'memberId' => $this->MemberID,
            'values' => $values
        ];
    }
    
    
    
    /* Commentable */
    public function canViewComments($member) { return true; }
    
    public function canCreateComment($member) { return true; }
}
