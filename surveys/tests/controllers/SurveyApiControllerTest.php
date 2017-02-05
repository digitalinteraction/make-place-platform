<?php

/** ... */
class SurveyApiControllerTest extends FunctionalTest {
    
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
    public function testSubmitRoute() {
        
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
        $response = SurveyResponse::get()->last();
        
        $this->assertNotNull($response);
    }
    
    public function testSubmitSurveyResponse() {
        
        // Create a response to the survey
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b'
        ]);
        
        $res = $this->post('s/1/submit', $data);
        $json = json_decode($res->getBody(), true);
        
        // See if a surveyResponse was created
        $response = SurveyResponse::get()->last();
        
        $this->assertEquals($response->toJson(), $json);
    }
    
    public function testSubmitFields() {
        
        // Create a response to the survey
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b'
        ]);
        
        $res = $this->post('s/1/submit', $data);
        
        // See if a surveyResponse was created
        $response = SurveyResponse::get()->last();
        
        $json = $response->jsonField('Responses');
        
        $this->assertEquals($data['Fields'], $json);
    }
    
    public function testSubmitWithLatAndLng() {
        
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b',
        ]);
        
        $data['ResponseLat'] = '10';
        $data['ResponseLng'] = '20';
        
        $res = $this->post('s/1/submit', $data);
        $json = json_decode($res->getBody(), true);
        
        
        // Check the lat and long were set & returned
        $this->assertEquals(10.0, $json['lat']);
        $this->assertEquals(20.0, $json['lng']);
    }
    
    public function testSubmitRedirectBack() {
        
        // Create a response to the survey
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b'
        ]);
        
        $data['RedirectBack'] = '1';
        
        $res = $this->post('s/1/submit', $data);
        
        
        // Test a non-success code is thrown
        // Can't test 301 because redirectback doesn't work with tests!
        $this->assertNotEquals(200, $res->getStatusCode());
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
    
    public function testSubmitWithInvalidSecurityKey() {
        
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
    
    public function testSubmitWithIncorrectSurveyID() {
        
        // Make another survey
        Survey::create()->write();
        
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b'
        ]);
        
        $res = $this->post('s/2/submit', $data);
        
        $this->assertEquals(404, $res->getStatusCode());
    }
    
    public function testSubmitWithIncorectLatLng() {
        
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b',
        ]);
        
        $data['ResponseLat'] = '10';
        
        // Don't set the lng
        
        $res = $this->post('s/1/submit', $data);
        
        $this->assertEquals(404, $res->getStatusCode());
    }
    
    
    
    /*
     *  Viewing surveys
     */
    public function testViewSurveyRoute() {
        
        $res = $this->get('s/1/view');
        
        $this->assertEquals(200, $res->getStatusCode());
    }
    
    public function testViewSurvey() {
        
        $res = $this->get('s/1/view');
        $json = json_decode($res->getBody(), true);
        
        $this->assertArrayHasKey('title', $json);
        $this->assertArrayHasKey('content', $json);
    }
    
    public function testViewSurveyWithLatLng() {
        
        $params = 'lat=54.980759337802&lng=-1.614518165588379';
        
        $res = $this->get("s/1/view?$params");
        $json = json_decode($res->getBody(), true);
        
        $content = $json['content'];
        
        // Assert 2 hidden fields were added to the form
        $this->assertRegExp('/ResponseLat.*54.980759337802/', $content);
        $this->assertRegExp('/ResponseLng.*-1.614518165588379/', $content);
    }
    
    public function testViewNullResponse() {
        
        $res = $this->get('s/0/view');
        
        $this->assertEquals(404, $res->getStatusCode());
    }
    
    
    
    /*
     *  Responses test
     */
    public function testGetResponses() {
        
        $res = $this->get('s/1/responses');
        $json = json_decode($res->getBody(), true);
        
        $this->assertEquals(2, count($json));
        
        $expected = [
            'id' => 1,
            'surveyId' => 1,
            'memberId' => 1,
            'lat' => 10.0,
            'lng' => 20.0,
            'responses' => [
                'question-a' => 'abc',
                'question-b' => '123'
            ]
        ];
        
        $this->assertEquals($expected, $json[0]);
    }
    
    public function testGetResponseWithGeo() {
        
        SurveyResponse::create([
            "SurveyID" => 1,
            "MemberID" => 2,
            "Responses" => '{"a": "b"}'
        ])->write();
        
        $res = $this->get('s/1/responses?onlygeo');
        $json = json_decode($res->getBody(), true);
        
        $this->assertEquals(2, count($json));
    }
    
    
    
    
    /*
     *  Test viewing responses
     */
    public function testViewResponseRoute() {
        
        $res = $this->get('s/1/r/1/view');
        
        $this->assertEquals(200, $res->getStatusCode());
    }
    
    public function testViewResponse() {
        
        $res = $this->get('s/1/r/1/view');
        $json = json_decode($res->getBody(), true);
        
        $expected = [
            'title',
            'content'
        ];
        
        $this->assertEquals($expected, array_keys($json));
    }
    
    
    
    /*
     *  Test Misc
     */
    /** @expectedException SS_HTTPResponse_Exception */
    public function testInit() {
        
        $controller = SurveyApiController::create();
        
        $response = $controller->init();
        
        $this->assertEquals(404, $response->getStatusCode());
    }
    
    public function testIndex() {
        
        $res = $this->get('s/1');
        
        $this->assertEquals(200, $res->getStatusCode());
    }
    
    public function testPostVar() {
        
        $controller = SurveyApiController::create();
        
        $errors = [];
        
        $value = $controller->postVar('something', $errors);
        
        $this->assertNull($value);
        $this->assertEquals(["Please provide 'something'"], $errors);
    }
    
    public function testGetVar() {
        
        $controller = SurveyApiController::create();
        
        $errors = [];
        
        $value = $controller->getVar('something', $errors);
        
        $this->assertNull($value);
        $this->assertEquals(["Please provide 'something'"], $errors);
    }
}
