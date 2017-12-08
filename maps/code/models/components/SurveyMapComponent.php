<?php

/** A component to add to a map to view and respond to a survey */
class SurveyMapComponent extends MapComponent {
    
    private static $extensions = [ 'PermsFieldExtension' ];
    
    private static $db = [
        'ActionColour' => 'Enum(array("primary", "secondary","blue", "green", "orange", "purple", "red"), "green")',
        'ActionMessage' => 'Varchar(255)',
        'PinColour' => 'Enum(array("blue", "green", "orange", "purple", "red"), "green")',
        'PositionQuestion' => 'Varchar(255)',
        'HighlightQuestion' => 'Varchar(255)',
        
        'RenderResponses' => 'Boolean',
        'ResponseTitle' => 'Varchar(255)',
        'ResponseMinimizable' => 'Boolean',
        'ResponseShareable' => 'Boolean',
        
        'VotingEnabled' => 'Boolean',
        'VoteTitle' => 'Varchar(255)',
        'VoteType' => 'Varchar(255)',
        'CommentingEnabled' => 'Boolean',
        'CommentTitle' => 'Varchar(255)',
        'CommentPlaceholder' => 'Varchar(255)',
        'CommentAction' => 'Varchar(255)',
        
        'VotingViewPerms' => 'Enum(array("Anyone","Member","NoOne","Group"), "Member")',
        'VotingMakePerms' => 'Enum(array("Member","NoOne","Group"), "Member")',
        'CommentViewPerms' => 'Enum(array("Anyone","Member","NoOne","Group"), "Member")',
        'CommentMakePerms' => 'Enum(array("Member","NoOne","Group"), "Member")'
    ];
    
    private static $has_one = [
        'Survey' => 'Survey'
    ];
    
    private static $many_many = [
        'VotingViewGroups' => 'Group',
        'VotingMakeGroups' => 'Group',
        'CommentViewGroups' => 'Group',
        'CommentMakeGroups' => 'Group'
    ];
    
    private static $defaults = [
        'ActionMessage' => 'Add Response',
        'ActionColour' => 'primary',
        'PinColour' => 'secondary',
        
        'ResponseTitle' => 'Survey Response',
        'ResponseMinimizable' => false,
        'ResponseShareable' => false,
        
        'VoteTitle' => 'What do you think?',
        'VoteType' => 'BASIC',
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
                ->setEmptyString('Not Selected')
        ]);
        
        
        // If there is a survey, add survey-specific fields
        if ($this->SurveyID != null) {
            
            // Find the question's geo fields
            $geoQuestions = $this->Survey()->Questions()
                ->filter('ClassName', 'GeoQuestion')
                ->map('Handle', 'Name')
                ->toArray();
            
            
            // Get permission maps
            $groupsMap = $this->groupsMap();
            $viewPerms = $this->viewPerms();
            $makePerms = $this->makePerms();
            
            
            
            // Add geometry question fields
            $fields->addFieldsToTab('Root.Survey.Geom', [
                DropdownField::create("PositionQuestion", 'Position Question', $geoQuestions)
                    ->setDescription("The question responsible for the survey's location")
                    ->setEmptyString('Not Selected'),
                DropdownField::create("HighlightQuestion", 'Highlight Question', $geoQuestions)
                    ->setDescription("The question to display extra geometries when selected")
                    ->setEmptyString('Not Selected'),
            ]);
            
            // Add appearance fields
            $fields->addFieldsToTab('Root.Survey.Appearance', [
                HeaderField::create('ResponseTitle', 4),
                CheckboxField::create('RenderResponses', 'Show responses?'),
                TextField::create('ResponseTitle', 'Response Title')
                    ->setDescription("The title displayed when viewing a response to the survey"),
                CheckboxField::create('ResponseMinimizable', 'If a response can be minimized'),
                CheckboxField::create('ResponseShareable', 'If a response can be shared'),
                
                
                HeaderField::create('Pins', 4),
                TextField::create('ActionMessage', 'Action')
                    ->setDescription("The action to add a response from the map"),
                DropdownField::create('ActionColour', 'Action Colour', $actionColours)
                    ->setDescription("The colour of the action on the map"),
                DropdownField::create('PinColour', 'Pin Colour', $pinColours)
                    ->setDescription("The colour of the response pins")
            ]);
            
            // Add interaction fields
            $fields->addFieldsToTab('Root.Survey.Comments', [
                
                HeaderField::create('CommentInfoHeader', 'Customisation', 3),
                CheckboxField::create('CommentingEnabled', 'Allow people to comment'),
                TextField::create('CommentTitle', 'Commenting Title'),
                TextField::create('CommentPlaceholder', 'Comment Placeholder'),
                TextField::create('CommentAction', 'Commenting Action')
                    ->setDescription('The action to post a comment'),
                
                HeaderField::create('CommentPermsHeader', 'Permissions', 3),
                DropdownField::create('CommentViewPerms', 'Who can view comments', $viewPerms),
                $commentViewGroups = ListboxField::create('CommentViewGroups', 'View Groups', $groupsMap)
                    ->setMultiple(true),
                DropdownField::create('CommentMakePerms', 'Who can comment', $makePerms),
                $commentMakeGroups = ListboxField::create('CommentMakeGroups', 'Make Groups', $groupsMap)
                    ->setMultiple(true)
            ]);
            
            $fields->addFieldsToTab('Root.Survey.Voting', [
                HeaderField::create('VoteInfoHeader', 'Customisation', 3),
                CheckboxField::create('VotingEnabled', 'Allow people to Vote'),
                TextField::create('VoteTitle', 'Voting Title'),
                DropdownField::create('VoteType', 'VoteType', Votable::$voting_types),
                
                HeaderField::create('VotingPermsHeader', 'Permissions', 3),
                DropdownField::create('VotingViewPerms', 'Who can view votes', $viewPerms),
                $voteViewGroups = ListboxField::create('VotingViewGroups', 'View Groups', $groupsMap)
                    ->setMultiple(true),
                DropdownField::create('VotingMakePerms', 'Who can vote', $makePerms),
                $voteMakeGroups = ListboxField::create('VotingMakeGroups', 'Make Groups', $groupsMap)
                    ->setMultiple(true)
            ]);
            
            
            $voteViewGroups->displayIf('VotingViewPerms')->isEqualTo('Group');
            $voteMakeGroups->displayIf('VotingMakePerms')->isEqualTo('Group');
            
            $commentViewGroups->displayIf('CommentViewPerms')->isEqualTo('Group');
            $commentMakeGroups->displayIf('CommentMakePerms')->isEqualTo('Group');
    
        }
    }
    
    
    public function customiseJson($json) {
        
        $json = parent::customiseJson($json);
        
        // Grab our related survey
        $survey = $this->Survey();
        
        
        // Get the current member and check they're verified
        $member = Member::currentUser();
        $verified = $member ? $member->getHasVerified() : false;
        
        // Unset values that we format
        unset($json['votingViewPerms']);
        unset($json['votingMakePerms']);
        unset($json['commentViewPerms']);
        unset($json['commentMakePerms']);
        
        // Add the permissions
        $json["permissions"] = [
            "response" => [
                "view" => $survey->ResponseViewPerms,
                "make" => $survey->ResponseMakePerms
            ],
            "voting" => [
                "view" => $this->VotingViewPerms,
                "make" => $this->VotingMakePerms
            ],
            "comments" => [
                "view" => $this->CommentViewPerms,
                "make" => $this->CommentMakePerms
            ]
        ];
        
        // Add processed permissions
        $json["canView"] = $this->checkPerm($survey->ResponseViewPerms, $survey->ResponseViewGroups());
        $json["canSubmit"] = $this->checkPerm($survey->ResponseMakePerms, $survey->ResponseMakeGroups());
        
        $json["canViewVotes"] = $this->checkPerm($this->VotingViewPerms, $this->VotingViewGroups());
        $json["canMakeVotes"] = $this->checkPerm($this->VotingMakePerms, $this->VotingMakeGroups());
        
        $json["canViewComments"] = $this->checkPerm($this->CommentViewPerms, $this->CommentViewGroups());
        $json["canMakeComments"] = $this->checkPerm($this->CommentMakePerms, $this->CommentMakeGroups());
        
        
        return $json;
    }
    
}
