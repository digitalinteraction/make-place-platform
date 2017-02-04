<?php

/** ... */
class SurveyResponseTest extends SapphireTest {
    
    protected static $fixture_file = "surveys/tests/fixtures/survey.yml";
    
    public function setUp() {
        parent::setUp();
        
        $this->Response = $this->objFromFixture('SurveyResponse', 'responseA');
    }
    
    public function testGetValues() {
        
        $json = json_encode([
            'question-a' => 'abc',
            'question-b' => '123'
        ]);
        
        $values = $this->Response->getValues();
        
        $this->assertEquals(2, $values->count());
    }
    
    public function testGetTitle() {
        
        $this->assertRegexp('/Geoff Testington/', $this->Response->getTitle());
    }
}
