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
        
        // Geom questions
        $this->assertNotNull($fields->fieldByName('Root.Survey.Geom.PositionQuestion'));
        $this->assertNotNull($fields->fieldByName('Root.Survey.Geom.HighlightQuestion'));
        
        // Appearance Questions
        $this->assertNotNull($fields->fieldByName('Root.Survey.Appearance.ActionMessage'));
        $this->assertNotNull($fields->fieldByName('Root.Survey.Appearance.ActionColour'));
        $this->assertNotNull($fields->fieldByName('Root.Survey.Appearance.PinColour'));
    }
    
    public function testCMSSurveyFieldWithoutSurvey() {
        
        // Set the survey id to null
        $this->component->SurveyID = null;
        
        // Get cms fields
        $fields = $this->component->getCMSFields();
        
        // Base fields - Should have these
        $this->assertNotNull($fields->fieldByName('Root.Main.SurveyCompHeader'));
        $this->assertNotNull($fields->fieldByName('Root.Main.SurveyID'));
        
        // Geom questions - Shouldn't have these
        $this->assertNull($fields->fieldByName('Root.Survey.Geom.PositionQuestion'));
        $this->assertNull($fields->fieldByName('Root.Survey.Geom.HighlightQuestion'));
        
        // Appearance questions - Shouldn't have these
        $this->assertNull($fields->fieldByName('Root.Survey.Appearance.ActionMessage'));
        $this->assertNull($fields->fieldByName('Root.Survey.Appearance.ActionColour'));
        $this->assertNull($fields->fieldByName('Root.Survey.Appearance.PinColour'));
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
            'actionColour' => 'green',
            'actionMessage' => 'Add Response',
            'pinColour' => 'blue',
            'positionQuestion' => 'position'
        ];
        
        $this->assertEquals($expected, $this->component->configData());
    }
    
    public function testConfigDataSignedIn() {
        
        $expected = [
            'type' => 'SurveyMapComponent',
            'surveyID' => 1,
            'canView' => true,
            'canSubmit' => true,
            'actionColour' => 'green',
            'actionMessage' => 'Add Response',
            'pinColour' => 'blue',
            'positionQuestion' => 'position'
        ];
        
        $this->assertEquals($expected, $this->component->configData());
    }
}
