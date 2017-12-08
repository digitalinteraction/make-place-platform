<?php

/** Tests HeatMapComponent */
/** @group whitelist */
class HeatMapComponentTest extends SapphireTest {
    
    /** @var HeatMapComponent */
    protected $component = null;
    protected $member = null;
    
    protected static $fixture_file = "maps/tests/fixtures/heatSurvey.yml";
    
    
    public function setUp() {
        parent::setUp();
        
        // Create a component to test
        $this->component = $this->objFromFixture('HeatMapComponent', 'a');
        $this->member = Member::get()->byID($this->logInWithPermission());
        
        GeoRef::$testMode = true;
    }
    
    public function tearDown() {
        GeoRef::$testMode = false;
        parent::tearDown();
    }
    
    
    public function testInit() {
        $this->assertNotNull($this->component);
    }
    
    public function testExtraFields() {
        
        $fields = $this->component->getCMSFields();
        
        // Chekc base fields exist
        $this->assertNotNull($fields->fieldByName('Root.Main.HeatmapCompHeader'));
        $this->assertNotNull($fields->fieldByName('Root.Main.SurveyID'));
        
        // Check question fields exist
        $this->assertNotNull($fields->fieldByName('Root.Survey.PositionQuestion'));
        $this->assertNotNull($fields->fieldByName('Root.Survey.WeightQuestion'));
        
        // Check heat question exist
        $this->assertNotNull($fields->fieldByName('Root.Heat.Radius'));
        $this->assertNotNull($fields->fieldByName('Root.Heat.Blur'));
        $this->assertNotNull($fields->fieldByName('Root.Heat.MaxIntensity'));
        $this->assertNotNull($fields->fieldByName('Root.Heat.MinOpacity'));
    }
    
    public function testCustomiseJson() {
        
        $expected = [
            'surveyID' => $this->component->SurveyID,
            'positionQuestion' => 'position',
            'weightQuestion' => 'weight',
            'radius' => 30,
            'blur' => 20,
            'maxIntensity' => 100,
            'minOpacity' => 0.2
        ];
        
        $this->assertArraySubset($expected, $this->component->jsonSerialize());
    }
    
    public function testJsonEmbedsResponses() {
        
        $json = $this->component->jsonSerialize();
        
        $this->assertArrayHasKey('points', $json);
        
        $this->assertCount(2, $json['points']);
    }
}
