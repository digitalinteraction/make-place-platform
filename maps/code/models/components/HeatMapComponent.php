<?php

/** A component to render responses from a survey as a heatmap */
class HeatMapComponent extends MapComponent implements CMSPreviewable {
    
    private static $extensions = [ 'PermsFieldExtension' ];
    
    private static $db = [
        'PositionQuestion' => 'Varchar(255)',
        'WeightQuestion' => 'Varchar(255)',
        'Radius' => 'Int',
        'Blur' => 'Int',
        'MaxIntensity' => 'Int',
        'MinOpacity' => 'Percentage',
        'DefaultWeight' => 'Percentage'
    ];
    
    private static $has_one = [
        'Survey' => 'Survey'
    ];
    
    private static $defaults = [
        'Radius' => 25,
        'Blur' => 15,
        'MinOpacity' => 0.3,
        'MaxIntensity' => 100
    ];
    
    public function addExtraFields(FieldList $fields) {
        
        $surveyList = Survey::get()->map()->toArray();
        
        
        $fields->addFieldsToTab('Root.Main', [
            HeaderField::create('HeatmapCompHeader', 'Heat Map Component', 2),
            DropdownField::create( 'SurveyID', 'Survey', $surveyList)
                ->setDescription('The survey to pull from')
                ->setEmptyString('Not Selected')
        ]);
        
        
        if ($this->SurveyID != null) {
            
            $geoQuestions = $this->Survey()->Questions()
                ->filter('ClassName', 'GeoQuestion')
                ->map('Handle', 'Name')
                ->toArray();
            
            $numberQuestions = $this->Survey()->Questions()
                ->filter('ClassName', 'NumberQuestion')
                ->map('Handle', 'Name')
                ->toArray();
            
            $fields->addFieldsToTab('Root.Survey', [
                DropdownField::create('PositionQuestion', 'Position Question', $geoQuestions)
                    ->setDescription('The question to use a a survey\'s position')
                    ->setEmptyString('Not Selected'),
                DropdownField::create('WeightQuestion', 'Weight Question', $numberQuestions)
                    ->setDescription('The question to use a a survey\'s weight')
                    ->setEmptyString('Not Selected')
            ]);
            
            $fields->addFieldsToTab('Root.Heat', [
                NumericField::create('Radius', 'Radius'),
                NumericField::create('Blur', 'Blur'),
                NumericField::create('MaxIntensity', 'Maximum Intensity'),
                NumericField::create('MinOpacity', 'Minimum Opacity')
            ]);
        }
        
    }
    
    public function customiseJson($json) {
        
        $json = parent::customiseJson($json);
        
        if (isset($json['minOpacity'])) {
            $json['minOpacity'] = (float)$json['minOpacity'];
        }
        
        $responses = SurveyResponse::get()->filter([
            'SurveyID' => $this->SurveyID,
            'Deleted' => false
        ]);
        
        $json['points'] = [];
        
        // $toPluck = [ $this->PositionQuestion ];
        // if ($this->WeightQuestion) {
        //     $toPluck[] = $this->WeightQuestion;
        // }
        //
        // foreach ($responses as $r) {
        //     $data = $r->toJson($toPluck)["values"];
        //
        //     $pos = $data[$this->PositionQuestion]['value'];
        //
        //     if ($pos === '') continue;
        //
        //     $point = [ 'pos' => $pos ];
        //     if ($this->WeightQuestion != null) {
        //         $point['weight'] = (float)$data[$this->WeightQuestion]['value'];
        //     }
        //     $json['points'][] = $point;
        // }
        
        return $json;
    }
    
    public function Link() {
        return $this->Page()->Link();
    }
    
    public function CMSEditLink() {
        return $this->Page()->CMSEditLink();
    }
}
