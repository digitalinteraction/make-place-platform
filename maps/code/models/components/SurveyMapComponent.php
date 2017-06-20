<?php

/** ... */
class SurveyMapComponent extends MapComponent {
    
    private static $db = [
        'ActionColour' => 'Enum(array("blue", "green", "orange", "purple", "red"), "green")',
        'ActionMessage' => 'Varchar(255)',
        'PinColour' => 'Enum(array("blue", "green", "orange", "purple", "red"), "blue")',
        'PositionQuestion' => 'Varchar(255)',
        'HighlightQuestion' => 'Varchar(255)',
        
        'ResponseTitle' => 'Varchar(255)',
        'ResponseMinimizable' => 'Boolean',
        'ResponseSharable' => 'Boolean',
        
        'VotingEnabled' => 'Boolean',
        'VoteTitle' => 'Varchar(255)',
        'CommentingEnabled' => 'Boolean',
        'CommentTitle' => 'Varchar(255)',
        'CommentPlaceholder' => 'Varchar(255)',
        'CommentAction' => 'Varchar(255)'
    ];
    
    private static $has_one = [
        'Survey' => 'Survey'
    ];
    
    private static $defaults = [
        'ActionMessage' => 'Add Response',
        'ActionColour' => 'green',
        'PinColour' => 'blue',
        
        'ResponseTitle' => 'Survey Response',
        'ResponseMinimizable' => false,
        'ResponseSharable' => false,
        
        'VoteTitle' => 'What do you think?',
        'CommentTitle' => 'Comments',
        'CommentAction' => 'Send'
    ];
    
    public function addExtraFields(FieldList $fields) {
        
        $surveyList = Survey::get()->map()->toArray();
        $actionColours = singleton('SurveyMapComponent')->dbObject('ActionColour')->enumValues();
        $pinColours = singleton('SurveyMapComponent')->dbObject('PinColour')->enumValues();
        
        
        $fields->addFieldsToTab('Root.Main', [
          HeaderField::create("SurveyCompHeader", "Survey Component", 2),
            DropdownField::create( 'SurveyID', 'Survey', $surveyList)
                ->setDescription("The survey to add to the map")
        ]);
        
        // If there is a survey, add survey-specific fields
        if ($this->SurveyID != null) {
            
            // Find the question's geo fields
            $geoQuestions = $this->Survey()->Questions()
                ->filter('ClassName', 'GeoQuestion')
                ->map('Handle', 'Name')
                ->toArray();
            $geoQuestions[''] = 'None';
            
            // Add geometry question fields
            $fields->addFieldsToTab('Root.Survey.Geom', [
                DropdownField::create("PositionQuestion", 'Position Question', $geoQuestions)
                    ->setDescription("The question responsible for the survey's location"),
                DropdownField::create("HighlightQuestion", 'Highlight Question', $geoQuestions)
                    ->setDescription("The question to display extra geometries when selected"),
            ]);
            
            // Add appearance fields
            $fields->addFieldsToTab('Root.Survey.Appearance', [
                TextField::create('ActionMessage', 'Action')
                    ->setDescription("The action to add a response from the map"),
                DropdownField::create('ActionColour', 'Action Colour', $actionColours)
                    ->setDescription("The colour of the action on the map"),
                DropdownField::create('PinColour', 'Pin Colour', $pinColours)
                    ->setDescription("The colour of the response pins"),
                
                TextField::create('ResponseTitle', 'Response Title')
                    ->setDescription("The title displayed when viewing a response to the survey"),
                CheckboxField::create('ResponseMinimizable', 'If a response can be minimized'),
                CheckboxField::create('ResponseSharable', 'If a response can be shared')
            ]);
            
            // Add interaction fields
            $fields->addFieldsToTab('Root.Survey.Interaction', [
                HeaderField::create('VoteHeader', 'Voting', 3),
                CheckboxField::create('VotingEnabled', 'Allow Voting'),
                TextField::create('VoteTitle', 'Voting Title'),
                
                HeaderField::create('CommentHeader', 'Commenting', 3),
                CheckboxField::create('CommentingEnabled', 'Allow Commenting'),
                TextField::create('CommentTitle', 'Commenting Title'),
                TextField::create('CommentPlaceholder', 'Comment Placeholder'),
                TextField::create('CommentAction', 'Commenting Action')
                    ->setDescription('The action to post a comment'),
            ]);
        }
    }
    
    
    public function configData() {
        
        // Start with the base config
        $data = parent::configData();
        
        $survey = $this->Survey();
        
        
        // Add the survey id to the config
        $data += [
            'surveyID' => $survey->ID,
            'actionColour' => $this->ActionColour,
            'actionMessage' => $this->ActionMessage,
            'pinColour' => $this->PinColour,
            'positionQuestion' => $this->PositionQuestion,
            
            'responseTitle' => $this->ResponseTitle,
            'responseTitle' => $this->ResponseTitle,
            'responseMinimizable' => $this->ResponseMinimizable,
            'responseShareable' => $this->ResponseShareable,
            
            'voteTitle' => $this->VoteTitle,
            'commentTitle' => $this->CommentTitle,
            'commentAction' => $this->CommentAction,
            'commentPlaceholder' => $this->CommentPlaceholder,
        ];
        
        if ($this->HighlightQuestion != null) {
            $data['highlightQuestion'] = $this->HighlightQuestion;
        }
        
        
        // Get the current member and check they're verified
        $member = Member::currentUser();
        $verified = $member ? $member->getHasVerified() : false;
        
        
        // Add auth config to the component
        $data["canView"] = $verified || $survey->ViewAuth == "None";
        $data["canSubmit"] = $verified || $survey->SubmitAuth == "None";
        
        // Add vote & comments if verified
        $data["canVote"] = (bool)($verified && $this->VotingEnabled);
        $data["canComment"] = (bool)($verified && $this->CommentingEnabled);
        
        
        return $data;
    }
    
}
