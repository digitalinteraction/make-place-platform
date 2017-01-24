<?php

/***/
class SurveyControllerTest extends FunctionalTest {
    
    protected static $fixture_file = "surveys/tests/fixtures/survey.yml";
    
    protected $survey = null;
    
    public function setUp() {
        
        parent::setUp();
        
        
        $this->survey = $this->objFromFixture('Survey', 'surveyA');
    }
    
    public function testFixtures() {
        
        $this->assertNotNull($this->survey);
        $this->assertEquals(2, $this->survey->Questions()->count());
    }
    
    public function testSubmitRoute() {
        
        // Create a response to the survey
        $data = [
            'id' => $this->survey->ID,
            'fields' => [
                'question-a' => 'answer-a',
                'question-b' => 'answer-b'
            ]
        ];
        
        $res = $this->post('s/1/submit', $data);
        
        $this->assertEquals(200, $res->getStatusCode());
    }
}
