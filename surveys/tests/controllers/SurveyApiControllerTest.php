<?php


class MockValidationQuestion extends Question {
    public function validateValue($value) { return ["Error"]; }
}

class MockPackingQuestion extends Question {
    public function packValue($value) { return "packed"; }
}

class MockResponseCreatedQuestion extends Question {
    public static $called = false;
    public function responseCreated($response, $value) { self::$called = true; }
}

/** Tests SurveyApiController */
class SurveyApiControllerTest extends FunctionalTest {
    
    protected static $fixture_file = "surveys/tests/fixtures/survey.yml";
    
    protected $survey = null;
    
    
    
    /* Test Lifecycle */
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
    
    
    
    /* Basic Submission tests */
    public function testSubmitRoute() {
        
        // Create a response to the survey
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b'
        ]);
        
        
        $res = $this->post('survey/1/submit', $data);
        
        $this->assertEquals(200, $res->getStatusCode());
    }
    
    public function testSubmitSurvey() {
        
        // Create a response to the survey
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b'
        ]);
        
        $res = $this->post('survey/1/submit', $data);
        
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
        
        $res = $this->post('survey/1/submit', $data);
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
        
        $res = $this->post('survey/1/submit', $data);
        
        // See if a surveyResponse was created
        $response = SurveyResponse::get()->last();
        
        $json = $response->jsonField('Responses');
        
        $this->assertEquals($data['Fields'], $json);
    }
    
    public function testSubmitRedirectBack() {
        
        // Create a response to the survey
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b'
        ]);
        
        $data['RedirectBack'] = '1';
        
        $res = $this->post('survey/1/submit', $data);
        
        
        // Test a non-success code is thrown
        // Can't test 301 because redirectback doesn't work with tests!
        $this->assertNotEquals(200, $res->getStatusCode());
    }
    
    
    
    /* Submission edge cases */
    public function testSubmitRequiresLoginWhenSet() {
        
        $this->member->logOut();
        
        // Create a response to the survey
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b'
        ]);
        
        $res = $this->post('survey/1/submit', $data);
        
        $this->assertEquals(401, $res->getStatusCode());
    }
    
    public function testSubmitWithoutAuth() {
        
        $this->survey->SubmitAuth = "None";
        $this->survey->write();
        
        $this->member->logOut();
        
        // Create a response to the survey
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b'
        ]);
        
        $res = $this->post('survey/1/submit', $data);
        
        $this->assertEquals(200, $res->getStatusCode());
    }
    
    public function testSubmitSurveyMustExist() {
        
        // Create a response to the survey
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b'
        ]);
        
        $res = $this->post('survey/1000/submit', $data);
        
        // Check the response failed
        $this->assertEquals(404, $res->getStatusCode());
    }
    
    public function testSubmitWithInvalidSecurityKey() {
        
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b'
        ]);
        
        $data['SecurityID'] = 'Error';
        
        $res = $this->post('survey/1/submit', $data);
        
        $this->assertEquals(401, $res->getStatusCode());
    }
    
    public function testSubmitFailsOnGet() {
    
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b'
        ]);
    
        $url = 'survey/1/submit' . http_build_query($data, '?');
    
        $res = $this->get($url);
    
        $this->assertEquals(404, $res->getStatusCode());
    }
    
    public function testSubmitFailsFromValidation() {
        
        $this->survey->Questions()->add(MockValidationQuestion::create(["Handle" => "failing-question"]));
        
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b',
            'failing-question' => 'Something'
        ]);
        
        $res = $this->post('survey/1/submit', $data);
        
        $this->assertEquals(400, $res->getStatusCode());
        $this->assertEquals('["Error"]', $res->getBody());
    }
    
    public function testSubmitMultipleQuestionErrors() {
        
        // A survey with 2 failing questions
        $this->survey->Questions()->addMany([
            MockValidationQuestion::create(["Handle" => "failing-q1"]),
            MockValidationQuestion::create(["Handle" => "failing-q2"])
        ]);
        
        // The data to post
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b',
            'failing-q1' => 'Something',
            'failing-q2' => 'Something'
        ]);
        
        // Post the response & decode the json
        $res = $this->post('survey/1/submit', $data);
        $json = json_decode($res->getBody(), true);
        
        // Check it returned 2 arrays
        $this->assertEquals(2, count($json));
    }
    
    public function testSubmitUsesQuestionPacking() {
        
        $this->survey->Questions()->add(MockPackingQuestion::create(["Handle" => "packing-question"]));
        
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b',
            'packing-question' => 'Something'
        ]);
        
        $res = $this->post('survey/1/submit', $data);
        
        
        $response = SurveyResponse::get()->last();
        $json = $response->jsonField('Responses');
        
        $this->assertEquals("packed", $json["packing-question"]);
    }
    
    public function testSubmitUsesResponseCreatedCallback() {
        
        $this->survey->Questions()->add(MockResponseCreatedQuestion::create(["Handle" => "response-created-question"]));
        
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b',
            'response-created-question' => 'Something'
        ]);
        
        $res = $this->post('survey/1/submit', $data);
        
        $this->assertTrue(MockResponseCreatedQuestion::$called);
        
        MockResponseCreatedQuestion::$called = false;
    }
    
    public function testSubmitWithApiAuth() {
        
        // Log out the current member
        $this->member->logOut();
        
        // Create an apikey to auth with
        $apikey = ApiKey::create(["Key" => "secret", "MemberID" => $this->member->ID]);
        $apikey->write();
        
        // Create a response to the survey
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b'
        ]);
        
        $res = $this->post('survey/1/submit?apikey=secret', $data);
        
        $this->assertEquals(200, $res->getStatusCode());
    }
    
    public function testSubmitWithCreatedDate() {
        
        // Create a response to the survey
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b'
        ]);
        
        $data["Created"] = "2017-08-22 16:31:00";
        
        $res = $this->post('survey/1/submit', $data);
        
        // See if a surveyResponse was created
        $response = SurveyResponse::get()->last();
        
        $this->assertEquals("2017-08-22 16:31:00", $response->Created);
    }
    
    public function testSubmitWithInvalidCreatedDate() {
        
        // Create a response to the survey
        $data = $this->survey->generateData([
            'question-a' => 'answer-a',
            'question-b' => 'answer-b'
        ]);
        
        $data["Created"] = "Not a date";
        
        $res = $this->post('survey/1/submit', $data);
        
        // See if a surveyResponse was created
        $response = SurveyResponse::get()->last();
        
        $this->assertEquals(400, $res->getStatusCode());
    }
    
    
    
    /* Viewing surveys */
    public function testViewSurveyRoute() {
        
        $res = $this->get('survey/1/view');
        
        $this->assertEquals(200, $res->getStatusCode());
    }
    
    public function testViewSurvey() {
        
        $res = $this->get('survey/1/view');
        $json = json_decode($res->getBody(), true);
        
        $this->assertArrayHasKey('title', $json);
        $this->assertArrayHasKey('content', $json);
        
        $this->assertEquals("Some Survey", $json["title"]);
    }
    
    public function testViewSurveyRequiresLogin() {
        
        $this->member->logOut();
        
        $res = $this->get('survey/1/view');
        $json = json_decode($res->getBody(), true);
        
        $this->assertEquals("Please log in", $json['title']);
    }
    
    public function testViewNullResponse() {
        
        $res = $this->get('survey/0/view');
        
        $this->assertEquals(404, $res->getStatusCode());
    }
    
    
    
    /* Responses test */
    public function testGetResponses() {
        
        $res = $this->get('survey/1/responses');
        $json = json_decode($res->getBody(), true);
        
        $this->assertEquals(2, count($json));
        
        $expected = [
            'id' => 1,
            'surveyId' => 1,
            'memberId' => 1,
            'values' => [
                'question-a' => [ 'name' => 'Question A', 'value' => 'abc' ],
                'question-b' => [ 'name' => 'Question B', 'value' => '123' ],
             ]
        ];
        
        $this->assertArraySubset($expected, $json[0]);
    }
    
    public function testGetResponsesRequiresAuthWhenSet() {
        
        $this->survey->ViewAuth = "Member";
        $this->survey->write();
        
        $this->member->logOut();
        
        $res = $this->get('survey/1/responses');
        
        $this->assertEquals(401, $res->getStatusCode());
    }
    
    
    
    /* Test viewing responses */
    public function testViewResponseRoute() {
        
        $res = $this->get('survey/1/response/1');
        
        $this->assertEquals(200, $res->getStatusCode());
    }
    
    public function testViewResponse() {
        
        $res = $this->get('survey/1/response/1');
        $json = json_decode($res->getBody(), true);
        
        $expected = [
            'response',
            'member'
        ];
        
        $this->assertNotNull($expected, array_keys($json));
    }
    
    
    
    /* Creating geometries */
    public function testCreateGeomRoute() {
        
        $params = $this->survey->generateData([]);
        $res = $this->post('survey/1/geo', $params);
        
        // Check the request was not a 404 - not found
        $this->assertNotEquals(404, $res->getStatusCode());
    }
    
    public function testCreateGeomFailsWithoutAuth() {
        
        $this->member->logOut();
        $res = $this->post('survey/1/geo', []);
        
        // Check we got a 401 - Unauthorised
        $this->assertEquals(401, $res->getStatusCode());
    }
    
    public function testCreateGeomFailsWithoutParams() {
        
        $params = $this->survey->generateData([]);
        $res = $this->post('survey/2/geo', $params);
        
        // Check the request failed without params
        $this->assertEquals(400, $res->getStatusCode());
    }
    
    public function testCreateGeomFailsWithInvalidQuestion() {
        
        $params = $this->survey->generateFormData([
            "question" => 'question-d'
        ]);
        
        $res = $this->post('survey/2/geo', $params);
        
        // Check the request failed with an invalid question
        $this->assertEquals(400, $res->getStatusCode());
    }
    
    public function testCreateGeomSuccess() {
        
        // Don't actually go creating a geo record
        GeoRef::$testMode = true;
        
        $params = $this->survey->generateFormData([
            "question" => "question-c",
            "type" => "POINT",
            "geom" => [ "x" => 50, "y" => -1 ]
        ]);
        
        $res = $this->post('survey/2/geo', $params);
        
        // Check the request was successful
        $this->assertEquals(200, $res->getStatusCode());
        
        $json = json_decode($res->getBody(), true);
        $this->assertEquals(["id" => "1"], $json);
        
        // Turn off test mode
        GeoRef::$testMode = false;
    }
    
    
    
    /* Creating Media */
    public function testCreateMediaRoute() {
        
        $params = $this->survey->generateFormData([]);
        $res = $this->post('survey/1/media', $params);
        
        // Check the request was not a 404 - not found
        $this->assertNotEquals(404, $res->getStatusCode());
    }
    
    public function testCreateMediaSuccess() {
        
        // Pretend a file was uploaded
        $_FILES["question-d"] = [
            "name" => "image.png",
            "type" => "image/png",
            "tmp_name" => "/tmp/test_image.png",
            "error" => 0,
            "size" => 13181
        ];
        
        // Put file in a temporary location
        $file = fopen($_FILES["question-d"]["tmp_name"], "w");
        fwrite($file, "Some dummy data\n");
        fclose($file);
        
        // The params for the request
        $params = $this->survey->generateFormData([
            "question" => "question-d",
        ]);
        
        // Perform the request
        $res = $this->post('survey/2/media', $params);
        
        // Check the response was sucessful
        $this->assertEquals(200, $res->getStatusCode());
        
        // Check it returned a json id
        $json = json_decode($res->getBody(), true);
        $this->assertEquals(["id" => "1"], $json);
        
        unset($_FILES["question-d"]);
    }
    
    public function testCreateMediaFailsWithoutAuth() {
        
        $this->member->logOut();
        $res = $this->post('survey/1/media', []);
        
        // Check we got a 401 - Unauthorised
        $this->assertEquals(401, $res->getStatusCode());
    }
    
    public function testCreateMediaWithoutFile() {
        
        // The params for the request
        $params = $this->survey->generateFormData([
            "question" => "question-d",
        ]);
        
        // Perform the request
        $res = $this->post('survey/2/media', $params);
        
        // Check the response was sucessful
        $this->assertEquals(400, $res->getStatusCode());
    }
    
    public function testCreateMediaWithInvalidFile() {
        
        // Pretend a file was uploaded
        $_FILES["question-d"] = [
            "Invalid" => "values"
        ];
        
        // The params for the request
        $params = $this->survey->generateFormData([
            "question" => "question-d",
        ]);
        
        // Perform the request
        $res = $this->post('survey/2/media', $params);
        
        // Check the response was sucessful
        $this->assertEquals(400, $res->getStatusCode());
    }
    
    
    
    /* Test Misc */
    public function testInit() {
        
        $this->setExpectedException(SS_HTTPResponse_Exception::class);
        
        $controller = SurveyApiController::create();
        
        $response = $controller->init();
        
        $this->assertEquals(404, $response->getStatusCode());
    }
    
    public function testIndex() {
        
        $res = $this->get('survey/1');
        
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
    
    public function testJsonVar() {
        
        $body = json_encode(["SomeKey" => "SomeValue"]);
        $controller = SurveyApiController::create();
        $controller->setRequest(new SS_HTTPRequest("POST", "localhost", [], [], $body));
        
        $value = $controller->jsonVar("SomeKey");
        
        $this->assertEquals("SomeValue", $value);
    }
    
    public function testJsonVarErrors() {
        
        $body = json_encode([]);
        $controller = SurveyApiController::create();
        $controller->setRequest(new SS_HTTPRequest("POST", "localhost", [], [], $body));
        
        $errors = [];
        $value = $controller->jsonVar("something", $errors);
        
        $this->assertNull($value);
        $this->assertEquals(["Please provide 'something'"], $errors);
    }
    
    public function testBodyVarUsesPostAndJson() {
        
        $body = json_encode(["JsonKey" => "JsonValue"]);
        $post = ["PostKey" => "PostValue"];
        
        $controller = SurveyApiController::create();
        $controller->setRequest(new SS_HTTPRequest("POST", "localhost", [], $post, $body));
        
        $jsonValue = $controller->bodyVar("JsonKey");
        $postValue = $controller->bodyVar("PostKey");
        
        $this->assertEquals("JsonValue", $jsonValue);
        $this->assertEquals("PostValue", $postValue);
    }
    
    public function testGetQuestionFromRequest() {
        
    }
}
