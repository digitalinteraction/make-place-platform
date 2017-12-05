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
        
        'ResponseTitle' => 'Varchar(255)',
        'ResponseMinimizable' => 'Boolean',
        'ResponseSharable' => 'Boolean',
        
        'VotingEnabled' => 'Boolean',
        'VoteTitle' => 'Varchar(255)',
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
            
            
            // Get permission maps
            $groupsMap = $this->groupsMap();
            $viewPerms = $this->viewPerms();
            $makePerms = $this->makePerms();
            
            
            
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
            'votingEnabled' => $this->VotingEnabled,
            'commentingEnabled' => $this->CommentingEnabled,
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
        
        // Add the permissions
        $data["permissions"] = [
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
        $data["canView"] = $this->checkPerm($survey->ResponseViewPerms, $survey->ResponseViewGroups());
        $data["canSubmit"] = $this->checkPerm($survey->ResponseMakePerms, $survey->ResponseMakeGroups());
        
        $data["canViewVotes"] = $this->checkPerm($this->VotingViewPerms, $this->VotingViewGroups());
        $data["canMakeVotes"] = $this->checkPerm($this->VotingMakePerms, $this->VotingMakeGroups());
        
        $data["canViewComments"] = $this->checkPerm($this->CommentViewPerms, $this->CommentViewGroups());
        $data["canMakeComments"] = $this->checkPerm($this->CommentMakePerms, $this->CommentMakeGroups());
        
        
        return $data;
    }
    
}
