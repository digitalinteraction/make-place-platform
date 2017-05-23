<?php

/** ... */
class SurveyApiController extends Controller {
    
    /**
     * @apiDefine Member Member access only
     * Authentication requires a valid web session or a valid `apikey`
     */
    
    /**
     * @apiDefine SurveyNotFound
     * @apiSuccessExample 404 Not Found
     * [ "Survey Not Found" ]
     */
    
    
    
    private static $allowed_actions = [
        'index', 'submitSurvey', 'getResponses', 'viewResponse', 'viewSurvey', 'createGeom', 'createMedia'
    ];
    
    private static $url_handlers = [
        'index' => 'index',
        'submit' => 'submitSurvey',
        'view' => 'viewSurvey',
        'responses' => 'getResponses',
        'response/$ResponseID' => 'viewResponse',
        'geo' => 'createGeom',
        'media' => 'createMedia'
    ];
    
    public function init() {
        
        parent::init();
        
        $surveyId = $this->request->param('SurveyID');
        $this->Survey = Survey::get()->byID($surveyId);
        
        if ($this->Survey == null) {
            return $this->httpError(404, "Survey not found");
        }
        
        return $this;
    }
    
    
    /**
     * @api {get} survey/:id/ Questions
     * @apiName SurveyIndex
     * @apiGroup Survey
     * @apiPermission Member
     *
     * @apiDescription Gets information about a survey and the questions it asks
     *
     * @apiParam {int} id The id of the survey to fetch
     *
     * @apiSuccessExample {json} 200 OK
     * {
     *   "name": "Bike Accidents",
     *   "questions": {
     *     "what-happened": { "type": "TextQuestion" },
     *     "bike-type": {
     *       "type": "DropdownQuestion",
     *       "options": [
     *         "Mountain Bike",
     *         "Road Bike",
     *         "Motorbike",
     *         "Hybrid",
     *         "Other"
     *       ]
     *     },
     *     "comments": { "type": "Question" },
     *     "position": {
     *       "type": "GeoQuestion",
     *       "geoType": "POINT",
     *       "dataType": 1
     *     },
     *     "reflection": { "type": "MediaQuestion" }
     *   }
     * }
     *
     * @apiUse SurveyNotFound
     */
    public function index($request) {
        
        $questions = $this->Survey->getQuestionMap();
        $fields = [];
        foreach ($questions as $handle => $question) {
            $fields[$handle] = $question->sample();
        }
        
        return $this->jsonResponse([
            "name" => $this->Survey->Name,
            "questions" => $fields
        ]);
        
        return $this->Survey->Name . ': index';
    }
    
    /**
     * @api {post} survey/:id/submit Submit Response
     * @apiName SurveySubmit
     * @apiGroup Survey
     * @apiPermission Member
     *
     * @apiDescription Submits a response to a survey.
     * You post the answers to each question in the survey where each question type
     * validates the response you gave it. You can get the questions to answer
     * from `/survey/:id`
     *
     * Geo & Media questions allow you to post
     * the raw response or an id to a Geo or Media object created with
     *  `/survey/:id/geo` or `/survey/:id/media`
     *
     * When uploading files to respond to a MediaQuestion,
     * make sure the file is uploaded under the question-handle
     *
     * For GeoQuestion response structure see `survey/:id/geo`
     *
     * @apiParam {int} id The id of the survey to submit to
     * @apiParam {Object} Fields The responses to submit with each question as a key
     *
     * @apiParamExample {json} Json Example
     * {
     *   "Fields": {
     *     "what-happened": "something",
     *     "bike-type": "mountain",
     *     "reflection": 13,
     *     "position": { "type": "POINT", "x": 50.1, "y": -1.2 }
     *   }
     * }
     *
     * @apiSuccessExample {json} 200 OK
     * {
     *   "id": 21,
     *   "surveyId": 1,
     *   "memberId": 4,
     *   "values": {
     *     "what-happened": { "name": "What happened", "value": "thing" },
     *     "bike-type": { "name": "Bike Type", "value": "mountain" },
     *     "comments": { "name": "Comments", "value": "" },
     *     "position": {
     *       "name": "Position",
     *       "value": {
     *         "id": 53,
     *         "geom": { "x": 50.1, "y": -1.2},
     *         "type": "POINT"
     *       }
     *     },
     *     "reflection": {
     *       "name": "Reflection",
     *       "value": { "ID": 13 }
     *     }
     *   }
     * }
     *
     * @apiUse SurveyNotFound
     */
    public function submitSurvey() {
        
        $errors = [];
        
        // Check the authentication of the request
        $memberId = $this->requestMemberId($errors);
        if ($memberId === null) {
            return $this->jsonResponse($errors, 401);
        }
        
        $redirectBack = $this->bodyVar('RedirectBack') != null;
        $fields = $this->bodyVar('Fields', $errors);
        $created = $this->bodyVar('Created');
        
        if ($created) {
            
            // If set, check it is a valid date
            if (!DateTime::createFromFormat('Y-m-d H:i:s', $created)) {
                $errors[] = "'Created' date is invalid";
            }
        }
        
        if (count($errors) > 0) {
            return $this->jsonResponse($errors, 400);
        }
        
        // Process files
        foreach ($_FILES as $name => $file) {
            if ($file["error"] == UPLOAD_ERR_OK) {
                $fields[$name] = $file;
            }
        }
        
        
        // Get the map of handle to question objects
        $questionMap = $this->Survey->getQuestionMap();
        
        
        // Validate each question with the passed value or null
        foreach ($questionMap as $field => $question) {
            $errors = array_merge($errors, $question->validateValue(isset($fields[$field]) ? $fields[$field] : null));
        }
        
        
        // If there were any errors upto this point, return them in a failed response
        if (count($errors) > 0) {
            return $this->jsonResponse($errors, 400);
        }
        
        
        // Let the questions pack their value
        $packedValues = [];
        foreach ($questionMap as $field => $question) {
            $packedValues[$field] = $question->packValue(isset($fields[$field]) ? $fields[$field] : null);
        }
        
        
        // Generate a SurveyResponse & save it
        $response = SurveyResponse::create([
            'Created' => $created ? $created : date('Y-m-d H:i:s'),
            'SurveyID' => $this->Survey->ID,
            'MemberID' => $memberId,
            'Responses' => $packedValues
        ]);
        
        
        // Let the questions perform post-create actions
        foreach ($questionMap as $field => $question) {
            $question->responseCreated($response, $packedValues[$field]);
        }
        
        
        // Save the response
        $response->write();
        
        
        // If not ajax or command-line, redirect back
        // IDEA: refactor to a parameter? e.g. RedirectURL? + check if on site
        if ($redirectBack != null) {
            return $this->redirectBack();
        }
        
        
        return $this->jsonResponse($response->toJson());
    }
    
    /**
     * @api {get} survey/:id/view View
     * @apiName SurveyView
     * @apiGroup Survey
     *
     * @apiDescription Gets a form to view a survey & fill it out
     *
     * @apiSuccessExample 200 OK
     * {
     *   "title": "Bike Accidents",
     *   "content": "&lt;form ...&gt; ... &lt;/form&gt;"
     * }
     *
     * @apiUse SurveyNotFound
     */
    public function viewSurvey() {
        
        $memberId = Member::currentUserID();
        $auth = $this->Survey->SubmitAuth;
        
        if ($auth == "Member" && $memberId == null) {
            
            return $this->jsonResponse([
                'title' => "Please log in",
                'content' => "<p>Sorry, you need to log in to do that!</p>"
            ], 401);
        }
        
        return $this->jsonResponse([
            'title' => $this->Survey->Name,
            'content' => $this->Survey->forTemplate()->getValue()
        ]);
    }
    
    /**
     * @api {get} survey/:id/responses Responses
     * @apiName SurveyResponses
     * @apiGroup Survey
     *
     * @apiDescription Gets the responses to a survey
     *
     * @apiSuccessExample 200 OK
     * [
     *   {
     *     "id": 2,
     *     "surveyId": 1,
     *     "memberId": 2,
     *     "values": {
     *       "what-happened": { "name": "What happened", "value": "I was hit by a van" },
     *       "bike-type": { "name": "Bike Type", "value": "motorbike" },
     *       "comments": { "name": "Comments", "value": "Lorem ipsum dolor ..." },
     *       "position": {
     *         "name": "Position",
     *         "value": {
     *           "id": 2,
     *           "geom": { "x": 54.981, "y": -1.618 },
     *           "deployment_id": 1,
     *           "data_type_id": 1,
     *           "type": "POINT"
     *         }
     *       },
     *       "reflection": { "name": "Reflection", "value": "" }
     *     }
     *   }
     * ]
     *
     * @apiUse SurveyNotFound
     */
    public function getResponses() {
        
        $responses = SurveyResponse::get()->filter("SurveyID", $this->Survey->ID);
        
        $memberId = Member::currentUserID();
        $auth = $this->Survey->ViewAuth;
        
        if ($auth == "Member" && $memberId == null) {
            
            return $this->jsonResponse(["You need to log in to do that"], 401);
        }
        
        
        $data = [];
        
        foreach ($responses as $r) {
            $data[] = $r->toJson();
        }
        
        return $this->jsonResponse($data);
    }
    
    /**
     * @api {get} survey/:id/response/:id View Response
     * @apiName SurveyResponseView
     * @apiGroup Survey
     *
     * @apiDescription Gets html to view a response to a survey
     *
     * @apiSuccessExample 200 OK
     * {
     *   "title": "Geoff's Response",
     *   "content": "&lt;div class='survey-response'&gt; ... &lt;/div&gt;"
     * }
     *
     * @apiUse SurveyNotFound
     */
    public function viewResponse() {
        
        $response = SurveyResponse::get()
            ->filter('ID', $this->request->param('ResponseID'))
            ->filter('SurveyID', $this->Survey->ID)
            ->first();
        
        if ($response == null) {
            return $this->jsonResponse(["Response not found"], 404);
        }
        
        $member = $response->Member();
        
        $rendered = $this->renderWith("SurveyResponse", [
            "Response" => $response
        ]);
        
        $name = ($member) ? $member->getName() : "Unknown";
        
        return $this->jsonResponse([
            "title" => "$name's Response",
            "body" => $rendered->getValue()
        ]);
    }
    
    /**
     * @api {post} survey/:id/geo Create Geometry
     * @apiName SurveyGeoCreate
     * @apiGroup Survey
     * @apiPermission Member
     *
     * @apiDescription Pre-create a geometry to pass to `survey/:id/submit`
     *
     * @apiParam {string} question The question this geometry is in response to
     * @apiParam {string="POINT","LINESTRING"} type The type of geometry to create
     * @apiParam {object} geom The geometry to create (see examples)
     *
     * @apiParamExample Point Json
     * {
     *   "question": "position",
     *   "type": "POINT",
     *   "geom": { "x": 50, "y": 1 }
     * }
     *
     * @apiParamExample Line Json
     * {
     *   "question": "position",
     *   "type": "LINESTRING",
     *   "geom": {
     *     "points": [
     *       {"x": 50, "y": -1 },
     *       {"x": 50.1, "y": -0.1 }
     *     ]
     *   }
     * }
     *
     * @apiSuccessExample 200 OK
     * { "id": 42 }
     *
     * @apiUse SurveyNotFound
     */
    public function createGeom() {
        
        $errors = [];
        
        // Fetch the member from the request or fail
        $memberId = $this->requestMemberId($errors);
        if ($memberId === null) {
            return $this->jsonResponse($errors, 401);
        }
        
        
        // Get request params or fail
        $question = $this->getQuestionFromRequest('question', 'GeoQuestion', $errors);
        $geom = $this->bodyVar('geom', $errors);
        $type = $this->bodyVar('type', $errors);
        if (count($errors)) {
            return $this->jsonResponse($errors, 400);
        }
        
        // Let the question validate the value
        $errors = $question->validateValue($geom);
        
        if (count($errors)) {
            return $this->jsonResponse($errors, 400);
        }
        
        return $this->jsonResponse([
            "id" => $question->packValue($geom)
        ]);
    }
    
    /**
     * @api {post} survey/:id/media Create Media
     * @apiName SurveyMediaCreate
     * @apiGroup Survey
     * @apiPermission Member
     *
     * @apiDescription Pre-create media to pass to `survey/:id/submit`.
     * Upload your media under the same name as the `question` being responded to
     *
     * @apiParam {string} question The question this geometry is in response to
     *
     * @apiSuccessExample 200 OK
     * { "id": 42 }
     *
     * @apiUse SurveyNotFound
     */
    public function createMedia() {
        
        $errors = [];
        
        // Check the member permissions
        $memberId = $this->requestMemberId($errors);
        if ($memberId === null) {
            return $this->jsonResponse($errors, 401);
        }
        
        
        // Get params from the request from the request or fail
        $question = $this->getQuestionFromRequest('question', 'MediaQuestion', $errors);
        if ($question == null) {
            return $this->jsonResponse($errors, 400);
        }
        
        $handle = $question->Handle;
        
        if (!isset($_FILES[$handle])) {
            return $this->jsonResponse([ "Please upload file to '$handle'" ], 400);
        }
        
        // Validate w/ question
        $value = $_FILES[$handle];
        $errors = $question->validateValue($value);
        if (count($errors)) {
            return $this->jsonResponse($errors, 400);
        }
        
        // Pack w/ question
        return $this->jsonResponse([
            "id" => $question->packValue($value)
        ]);
    }
    
    
    
    /* Utils */
    public function requestMemberId(&$errors) {
        
        // Try to use api auth first
        if ($this->checkApiAuth()) {
            
            // If authed with the api, get the newly authenticated member
            return Member::currentUserID();
        }
        
        $auth = $this->Survey->SubmitAuth;
        $memberId = Member::currentUserID();
        
        if ($auth === "Member" && $memberId == null) {
            $errors[] = "You need to be logged in to do that";
        }
        
        // Check the security token matches
        $token = $this->bodyVar('SecurityID');
        if ($token == null || $token != (new SecurityToken())->getValue()) {
            $errors[] = "Validation failed, please submit again";
        }
        
        return count($errors) == 0 ? $memberId : null;
    }
    
    public function bodyVar($name, &$errors = []) {
        
        $post = $this->postVar($name);
        if ($post != null) { return $post; }
        
        $json = $this->jsonVar($name);
        if ($json != null) { return $json; }
        
        $errors[] = "Please provide '$name'";
        return null;
    }
    
    public function postVar($name, &$errors = []) {
        
        if ($this->request->postVar($name) != null) {
            return $this->request->postVar($name);
        }
        
        $errors[] = "Please provide '$name'";
        return null;
    }
    
    public function jsonVar($name, &$errors = []) {
        
        if ($this->jsonBody == null) {
            $this->jsonBody = json_decode($this->request->getBody(), true);
        }
        
        if (isset($this->jsonBody[$name])) {
            return $this->jsonBody[$name];
        }
        
        $errors[] = "Please provide '$name'";
        
        return null;
    }
    
    public function getVar($name, &$errors = []) {
        
        if ($this->request->getVar($name) != null) {
            return $this->request->getVar($name);
        }
        
        $errors[] = "Please provide '$name'";
        return null;
    }
    
    public function getQuestionFromRequest($key, $type, &$errors = []) {
        
        // Get the question handle from the request
        $questionHandle = $this->bodyVar($key, $errors);
        
        // Stop if it wasn't passed
        if ($questionHandle == null) { return null; }
        
        // Check if the question was passed
        $questionMap = $this->Survey->getQuestionMap();
        if (!isset($questionMap[$questionHandle])) {
            $errors[] = "Invalid question '$questionHandle'";
            return null;
        }
        
        $question = $questionMap[$questionHandle];
        
        if ($question->ClassName != $type) {
            $errors[] = "Must be a '$type'";
            return null;
        }
        
        return $question;
    }
    
}
