<?php

/** A response to the questions of a survey */
class SurveyResponse extends DataObject {
    
    private static $extensions = [
        "JsonFieldExtension", "Commentable", "Votable", "Deletable"
    ];
    
    public $HiddenQuestion = false;
    
    private static $db = [
        "Responses" => "JsonText"
    ];
    
    private static $has_one = [
        "Member" => "Member",
        "Survey" => "Survey"
    ];
    
    private static $has_many = [
        "Comments" => "Comment.Target"
    ];
    
    private static $many_many = [
        "Geometries" => "GeoRef",
        "Media" => "SurveyMedia"
    ];
    
    private static $summary_fields = [
        "ID" => "ID",
        "Member.Name" => "Member",
        "Deleted" => "Deleted"
    ];
    
    
    public function getValues() {
        
        $responses = $this->jsonField('Responses');
        
        $values = ArrayList::create();
        
        $questions = $this->Survey()->Questions();
        
        foreach ($questions as $question) {
            
            $key = $question->Handle;
            
            if (isset($responses[$key]) && $question->ClassName !== 'HiddenQuestion') {
                
                $value = trim($responses[$key]);
                
                $values->push(ArrayData::create([
                    "Question" => $question,
                    "Key" => $key,
                    "Value" => $value,
                    "HasResponse" => (bool)($value !== null && $value !== ""),
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
    
    
    
    public function getCMSFields() {
        
        $fields = FieldList::create([TabSet::create('Root')]);
        
        // $fields = parent::getCMSFields();
        
        $responses = $this->jsonField('Responses');
        
        $fields->addFieldsToTab('Root.Main', [
            ReadonlyField::create("Member.Name")->setValue($this->Member()->Name),
            $this->deletedField(),
            LiteralField::create("ResponseLabel", "<strong>Values</strong>"),
            LiteralField::create("Responses", "<pre>" . json_encode($responses, JSON_PRETTY_PRINT) . "</pre>")
        ]);
        
        // $commentConfig = GridFieldConfig_Base::create()
        //     ->addComponent(new GridFieldEditButton());
        //
        // $fields->addFieldsToTab('Root.Comments', [
        //     GridField::create(
        //         "Comments",
        //         "Comments",
        //         $this->Comments(),
        //         $commentConfig
        //     )
        // ]);
        
        
        return $fields;
    }
    
    
    
    /* Commentable */
    public function canViewComments($member = null) { return true; }
    
    public function canCreateComment($member = null) { return true; }
    
    
    
    /* Votable */
    public function voteType() { return "EMOJI"; }
    
    public function canViewVotes($member = null) { return true; }
    
    public function canCreateVote($member = null) { return true; }
}
