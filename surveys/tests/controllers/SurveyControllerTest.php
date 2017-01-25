<?php

/** ... */
class SurveyControllerTest extends FunctionalTest {
    
    protected static $fixture_file = "surveys/tests/fixtures/survey.yml";
    
    protected $survey = null;
    
    
    
    /*
     *  Test Lifecycle
     */
    public function setUp() {
        
        parent::setUp();
        
        
        $this->member = Member::create([
            "Email" => "test@gmail.com"
        ]);
        $this->member->write();
        
        $this->logInAs($this->member);
        
        
        $this->survey = $this->objFromFixture('Survey', 'surveyA');
    }
    
    public function testFixtures() {
        
        $this->assertNotNull($this->survey);
        $this->assertEquals(2, $this->survey->Questions()->count());
    }
    
    
    
    /*
     *  Basic Submission tests
     */
    public function testSubmitRouter() {
        
        // Create a response to the survey
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b'
        ]);
        
        
        $res = $this->post('s/1/submit', $data);
        
        $this->assertEquals(200, $res->getStatusCode());
        
        
    }
    
    public function testSubmitSurvey() {
        
        // Create a response to the survey
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b'
        ]);
        
        $res = $this->post('s/1/submit', $data);
        
        // See if a surveyResponse was created
        $response = SurveyResponse::get()->first();
        
        $this->assertNotNull($response);
    }
    
    public function testSubmitFields() {
        
        // Create a response to the survey
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b'
        ]);
        
        $res = $this->post('s/1/submit', $data);
        
        // See if a surveyResponse was created
        $response = SurveyResponse::get()->first();
        
        $json = $response->jsonField('Responses');
        
        $this->assertEquals($data['Fields'], $json);
    }
    
    
    
    /*
     *  Submission edge cases
     */
    public function testSubmitRequiresLogin() {
        
        $this->member->logOut();
        
        // Create a response to the survey
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b'
        ]);
        
        $res = $this->post('s/1/submit', $data);
        
        // See if a surveyResponse was created
        $response = SurveyResponse::get()->first();
        
        $this->assertEquals(404, $res->getStatusCode());
    }
    
    public function testSubmitSurveyMustExist() {
        
        // Create a response to the survey
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b'
        ]);
        
        $data['SurveyID'] = '2';
        
        $res = $this->post('s/1/submit', $data);
        
        // Check the response failed
        $this->assertEquals(404, $res->getStatusCode());
    }
    
    public function testSubmitInvalidSecurityKey() {
        
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b'
        ]);
        
        $data['SecurityID'] = 'Error';
        
        $res = $this->post('s/1/submit', $data);
        
        $this->assertEquals(404, $res->getStatusCode());
    }
    
    public function testSubmitFailsOnGet() {
    
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b'
        ]);
        
        $url = 's/1/submit' . http_build_query($data, '?');
    
        $res = $this->get($url);
    
        $this->assertEquals(404, $res->getStatusCode());
    }
}
