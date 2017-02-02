<?php

/** ... */
class SurveyMapComponentTest extends SapphireTest {
    
    /** @var SurveyMapComponent */
    protected $component = null;
    
    // Load db objects from a file
    protected static $fixture_file = "maps/tests/fixtures/survey.yml";
    
    
    /*
     *  Test Lifecycle
     */
    public function setUp() {
        parent::setUp();
        
        // Create a component to test
        $this->component = $this->objFromFixture('SurveyMapComponent', 'componentA');
    }
    
    public function testInit() {
        
        $this->assertNotNull($this->component);
        
        $this->assertNotNull($this->component->Survey());
    }
    
    
    
    /*
     *  Test CMS Fields
     */
    public function testCMSSurveyField() {
        
        $fields = $this->component->getCMSFields();
        
        $this->assertNotNull($fields->fieldByName('Root.Main.SurveyID'));
    }
    
    
    
    /*
     *  Test Config Data
     */
    public function testConfigData() {
        
        $expected = [
            'type' => 'SurveyMapComponent',
            'surveyID' => 1
        ];
        
        $this->assertEquals($expected, $this->component->configData());
    }
}
