<?php

/** ... */
class SurveyApiController extends Controller {
    
    private static $allowed_actions = [
        'index', 'submitSurvey', 'getResponses', 'viewResponse', 'viewSurvey'
    ];
    
    private static $url_handlers = [
        'index' => 'index',
        'submit' => 'submitSurvey',
        'view' => 'viewSurvey',
        'responses' => 'getResponses',
        'r/$ResponseID' => 'viewResponse'
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
    
    public function index($request) {
        
        return $this->Survey->Name . ': index';
    }
    
    public function submitSurvey() {
        
        $errors = [];
        
        $memberId = Member::currentUserID();
        $fields = $this->postVar('Fields', $errors);
        $token = $this->postVar('SecurityID', $errors);
        $redirectBack = $this->postVar('RedirectBack') != null;
        
        $auth = $this->Survey->SubmitAuth;
        
        if ($auth === "Member" && $memberId == null) {
            $errors[] = "You need to be logged in to do that";
        }
        
        // Check the security token matches
        if ($token != (new SecurityToken())->getValue()) {
            $errors[] = "Validation failed, please submit again";
        }
        
        if (count($errors) > 0) {
            return $this->jsonResponse($errors, 401);
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
        foreach ($fields as $field => $value) {
            $fields[$field] = $questionMap[$field]->packValue($value);
        }
        
        
        // Generate a SurveyResponse & save it
        $response = SurveyResponse::create([
            'SurveyID' => $this->Survey->ID,
            'MemberID' => $memberId,
            'Responses' => $fields
        ]);
        
        
        // Save the response
        $response->write();
        
        
        // If not ajax or command-line, redirect back
        // IDEA: refactor to a parameter? e.g. RedirectURL? + check if on site
        if ($redirectBack != null) {
            return $this->redirectBack();
        }
        
        
        return $this->jsonResponse($response->toJson());
    }
    
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
    
    public function getResponses() {
        
        $responses = SurveyResponse::get()->filter("SurveyID", $this->Survey->ID);
        
        $memberId = Member::currentUserID();
        $auth = $this->Survey->ViewAuth;
        
        if ($auth == "Member" && $memberId == null) {
            
            return $this->jsonResponse(["You need to log in to do that"], 401);
        }
        
        
        // If 'onlygeo' is passed, non-placed responses are ignored
        if ($this->getRequest()->getVar("onlygeo") !== null) {
            
            // $responses = $responses->exclude([
            //     "Latitude" => 0.0,
            //     "Longitude" => 0.0
            // ]);
        }
        
        
        $data = [];
        
        foreach ($responses as $r) {
            $data[] = $r->toJson();
        }
        
        return $this->jsonResponse($data);
    }
    
    public function viewResponse() {
        
        $response = SurveyResponse::get()
            ->filter('ID', $this->request->param('ResponseID'))
            ->filter('SurveyID', $this->Survey->ID)
            ->first();
        
        if ($response == null) {
            return $this->httpError(404);
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
    
    
    
    /* Utils */
    public function postVar($name, &$errors = []) {
        
        if ($this->request->postVar($name) != null) {
            return $this->request->postVar($name);
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
}
