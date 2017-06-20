<?php

/** Tests SurveyMapComponent */
/** @group whitelist */
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
        $this->member = Member::currentUser();
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
        $this->assertNotNull($fields->fieldByName('Root.Survey.Appearance.ResponseSharable'));
    }
    
    public function testCMSInteractionFields() {
        
        $fields = $this->component->getCMSFields();
        
        $this->assertNotNull($fields->fieldByName("Root.Survey.Interaction.VoteTitle"));
        $this->assertNotNull($fields->fieldByName("Root.Survey.Interaction.VotingEnabled"));
        $this->assertNotNull($fields->fieldByName("Root.Survey.Interaction.CommentingEnabled"));
        $this->assertNotNull($fields->fieldByName("Root.Survey.Interaction.CommentTitle"));
        $this->assertNotNull($fields->fieldByName("Root.Survey.Interaction.CommentAction"));
        $this->assertNotNull($fields->fieldByName("Root.Survey.Interaction.CommentPlaceholder"));
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
    public function testConfigData() {
        
        // NOTE: not sure why we're logged in at this point ...
        $this->member->logOut();
        
        
        $expected = [
            'type' => 'SurveyMapComponent',
            'surveyID' => 1,
            'canView' => true,
            'canSubmit' => false,
            'positionQuestion' => 'position'
        ];
        
        $data = $this->component->configData();
        
        $this->assertArraySubset($expected, $data);
    }
    
    public function testAppearanceConfigData() {
        
        $expected = [
            'actionColour' => 'green',
            'actionMessage' => 'Add Response',
            'pinColour' => 'blue',
            'responseTitle' => 'A Response',
            'responseMinimizable' => true,
            'responseShareable' => false,
        ];
        
        $data = $this->component->configData();
        
        $this->assertArraySubset($expected, $data);
    }
    
    public function testInteractionConfigData() {
        
        $expected = [
            'voteTitle' => 'Voting',
            'commentTitle' => 'Comments',
            'commentAction' => 'Send',
            'commentPlaceholder' => '...'
        ];
        
        $data = $this->component->configData();
        
        $this->assertArraySubset($expected, $data);
    }
    
    public function testConfigDataSignedIn() {
        
        $expected = [
            'canView' => true,
            'canSubmit' => true,
        ];
        
        $data = $this->component->configData();
        
        $this->assertArraySubset($expected, $data);
    }
    
    public function testInteractionConfigDataSignedIn() {
        
        $expected = [
            'canVote' => true,
            'canComment' => true
        ];
        
        $data = $this->component->configData();
        
        $this->assertArraySubset($expected, $data);
    }
}
