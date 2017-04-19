<?php

/** ... */
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
        
        $this->assertNotNull($fields->fieldByName('Root.Main.SurveyID'));
    }
    
    
    
    /* Test Config Data */
    public function testConfigData() {
        
        // NOTE: not sure why we're logged in at this point ...
        $this->member->logOut();
        
        
        $expected = [
            'type' => 'SurveyMapComponent',
            'surveyID' => 1,
            'canView' => true,
            'canSubmit' => false
        ];
        
        $this->assertEquals($expected, $this->component->configData());
    }
    
    public function testConfigAddsGeoQuestionHandle() {
        
        $this->component->Survey()->Questions()->addMany([
            GeoQuestion::create(["Handle" => "geo-q", "GeoType" => "POINT"])
        ]);
        
        $config = $this->component->configData();
        
        $this->assertEquals("geo-q", $config["geoPointQuestion"]);
    }
    
    public function testConfigDataSignedIn() {
        
        $expected = [
            'type' => 'SurveyMapComponent',
            'surveyID' => 1,
            'canView' => true,
            'canSubmit' => true
        ];
        
        $this->assertEquals($expected, $this->component->configData());
    }
}
