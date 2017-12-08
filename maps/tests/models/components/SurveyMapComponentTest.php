<?php

/** Tests SurveyMapComponent */
class SurveyMapComponentTest extends SapphireTest {
    
    /** @var SurveyMapComponent */
    protected $component = null;
    protected $member = null;
    
    // Load db objects from a file
    protected static $fixture_file = "maps/tests/fixtures/survey.yml";
    
    
    /* Test Lifecycle */
    public function setUp() {
        parent::setUp();
        
        // Create a component to test
        $this->component = $this->objFromFixture('SurveyMapComponent', 'componentA');
        $this->member = Member::get()->byID($this->logInWithPermission());
    }
    
    public function tearDown() {
        parent::tearDown();
    }
    
    public function testInit() {
        
        $this->assertNotNull($this->component);
        
        $this->assertNotNull($this->component->Survey());
    }
    
    
    
    /* Test CMS Fields */
    public function testCMSSurveyField() {
        
        $fields = $this->component->getCMSFields();
        
        // Base fields
        $this->assertNotNull($fields->fieldByName('Root.Main.SurveyCompHeader'));
        $this->assertNotNull($fields->fieldByName('Root.Main.SurveyID'));
    }
    
    public function testCMSGeomFields() {
      
      $fields = $this->component->getCMSFields();
      
      // Geom questions
      $this->assertNotNull($fields->fieldByName('Root.Survey.Geom.PositionQuestion'));
      $this->assertNotNull($fields->fieldByName('Root.Survey.Geom.HighlightQuestion'));
    }
    
    public function testCMSAppearanceFields() {
        
        $fields = $this->component->getCMSFields();
        
        // Appearance Questions
        $this->assertNotNull($fields->fieldByName('Root.Survey.Appearance.ActionMessage'));
        $this->assertNotNull($fields->fieldByName('Root.Survey.Appearance.ActionColour'));
        $this->assertNotNull($fields->fieldByName('Root.Survey.Appearance.PinColour'));
        
        $this->assertNotNull($fields->fieldByName('Root.Survey.Appearance.ResponseTitle'));
        $this->assertNotNull($fields->fieldByName('Root.Survey.Appearance.ResponseMinimizable'));
        $this->assertNotNull($fields->fieldByName('Root.Survey.Appearance.ResponseShareable'));
    }
    
    public function testCMSVotingFields() {
        
        $fields = $this->component->getCMSFields();
        
        $this->assertNotNull($fields->fieldByName("Root.Survey.Voting.VoteTitle"));
        
        $this->assertNotNull($fields->fieldByName("Root.Survey.Voting.VotingViewPerms"));
        $this->assertNotNull($fields->fieldByName("Root.Survey.Voting.VotingViewGroups"));
        $this->assertNotNull($fields->fieldByName("Root.Survey.Voting.VotingMakePerms"));
        $this->assertNotNull($fields->fieldByName("Root.Survey.Voting.VotingMakeGroups"));
    }
    
    public function testCMSCommentsFields() {
        
        $fields = $this->component->getCMSFields();
        
        $this->assertNotNull($fields->fieldByName("Root.Survey.Comments.CommentTitle"));
        $this->assertNotNull($fields->fieldByName("Root.Survey.Comments.CommentAction"));
        $this->assertNotNull($fields->fieldByName("Root.Survey.Comments.CommentPlaceholder"));
        
        $this->assertNotNull($fields->fieldByName("Root.Survey.Comments.CommentViewPerms"));
        $this->assertNotNull($fields->fieldByName("Root.Survey.Comments.CommentMakePerms"));
        $this->assertNotNull($fields->fieldByName("Root.Survey.Comments.CommentViewGroups"));
        $this->assertNotNull($fields->fieldByName("Root.Survey.Comments.CommentMakeGroups"));
    }
    
    public function testCMSSurveyFieldWithoutSurvey() {
        
        // Set the survey id to null
        $this->component->SurveyID = null;
        
        // Get cms fields
        $fields = $this->component->getCMSFields();
        
        // Base fields - Should have these
        $this->assertNotNull($fields->fieldByName('Root.Main.SurveyCompHeader'));
        $this->assertNotNull($fields->fieldByName('Root.Main.SurveyID'));
        
        // Shouldn't have the survey tab if its not set
        $this->assertNull($fields->fieldByName('Root.Survey'));
    }
    
    
    
    /* Test Config Data */
    public function testBaseJson() {
        
        // NOTE: not sure why we're logged in at this point ...
        $this->member->logOut();
        
        
        $expected = [
            'type' => 'SurveyMapComponent',
            'surveyID' => 1,
            'canView' => true,
            'canSubmit' => false,
            'positionQuestion' => 'position'
        ];
        
        $data = $this->component->jsonSerialize();
        
        $this->assertArraySubset($expected, $data);
    }
    
    public function testAppearanceJson() {
        
        $expected = [
            'actionColour' => 'primary',
            'actionMessage' => 'Add Response',
            'pinColour' => 'secondary',
            'responseTitle' => 'A Response',
            'responseMinimizable' => true,
            'responseShareable' => false,
        ];
        
        $data = $this->component->jsonSerialize();
        
        $this->assertArraySubset($expected, $data);
    }
    
    public function testInteractionJson() {
        
        $expected = [
            'voteTitle' => 'Voting',
            'commentTitle' => 'Comments',
            'commentAction' => 'Send',
            'commentPlaceholder' => '...'
        ];
        
        $data = $this->component->jsonSerialize();
        
        $this->assertArraySubset($expected, $data);
    }
    
    public function testJsonSignedIn() {
        
        $expected = [
            'canView' => true,
            'canSubmit' => true,
        ];
        
        $data = $this->component->jsonSerialize();
        
        $this->assertArraySubset($expected, $data);
    }
    
    
    /* Test Permissions */
    public function testCheckPermsAnyone() {
        $this->assertTrue($this->component->checkPerm('Anyone'));
    }
    
    public function testCheckPermsNoOne() {
        $this->assertFalse($this->component->checkPerm('NoOne'));
    }
    
    public function testCheckPermsMember() {
        $this->assertTrue($this->component->checkPerm('Member'));
    }
    
    public function testCheckPermsMemberFails() {
        $this->member->logOut();
        $this->assertFalse($this->component->checkPerm('Member'));
    }
    
    public function testCheckPermsGroups() {
        
        $gid = Group::create([ "Code" => "TestGroup" ])->write();
        
        $this->member->Groups()->add($gid);
        
        $groups = Group::get()->filter("Code", "TestGroup");
        
        $this->assertTrue($this->component->checkPerm('Group', $groups));
    }
    
    public function testCheckPermsGroupsFails() {
        
        $gid = Group::create([ "Code" => "TestGroup" ])->write();
        
        $groups = Group::get()->filter("Code", "TestGroup");
        
        $this->assertFalse($this->component->checkPerm('Group', $groups));
    }
}
