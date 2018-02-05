<?php

/** An extension to make a class a Survey:Question-based filtering MapComponent */
class SurveyFilterExtension extends DataExtension {
    
    private static $db = [
        'Label' => 'Varchar(255)'
    ];
    
    private static $has_one = [
        'Survey' => 'Survey',
        'Question' => 'Question'
    ];
    
    public function labelField() {
        return TextField::create('Label', 'Label')
            ->setDescription('How the filter will be presented to the user');
    }
    
    public function surveyField() {
        
        $surveys = Survey::get()->map()->toArray();
        
        return DropdownField::create( 'SurveyID', 'Survey', $surveys)
            ->setDescription("The survey to add to the map")
            ->setEmptyString('Not Selected');
    }
    
    public function questionField($type) {
        
        $questions = $this->owner->Survey()->Questions()
            ->filter('ClassName', $type)
            ->map('ID', 'Name')
            ->toArray();
        
        return DropdownField::create('QuestionID', 'Question', $questions)
            ->setDescription('The question to filter by')
            ->setEmptyString('Not Selected');
    }
    
}
