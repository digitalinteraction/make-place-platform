<?php

/** ... */
class SurveyApiController extends Controller {
    
    private static $allowed_actions = [
        'index', 'submitSurvey', 'getResponses', 'viewResponse'
    ];
    
    private static $url_handlers = [
        'index' => 'index',
        'submit' => 'submitSurvey',
        'responses' => 'getResponses',
        'r/$ResponseID/view' => 'viewResponse'
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
        
        
        // Get parameters from the request
        $surveyId = $this->postVar('SurveyID', $errors);
        $userId = Member::currentUserID();
        $fields = $this->postVar('Fields', $errors);
        $token = $this->postVar('SecurityID', $errors);
        
        
        // Check there is a user logged in
        if ($userId == null) {
            $errors[] = "You need to be logged in to do that";
        }
        
        
        // Check the survey exists
        if (Survey::get()->byIDs([$surveyId])->exists() == false) {
            $errors[] = "That survey doesn't exist";
        }
        
        
        // Check the survey is the same as the one in the url
        if ($surveyId != $this->Survey->ID) {
            $errors[] = "Survey id mismatch passed '$surveyId' to url route '{$this->Survey->ID}'";
        }
        
        
        // Check the security token matches
        if ($token != (new SecurityToken())->getValue()) {
            $errors[] = "Validation failed, please submit again";
        }
        
        
        // If there is any errors, stop here
        if (count($errors) > 0) {
            // var_dump($errors);
            return $this->httpError(404);
        }
        
        // Generate a SurveyResponse & save it
        $response = SurveyResponse::create([
            'SurveyID' => $surveyId,
            'MemberID' => $userId,
            'UserID' => $userId,
            'Responses' => $fields,
        ]);
        
        $response->write();
        
        
        // If not ajax or command-line, redirect back
        if (isset($_SERVER['X_HTTP_REQUEST']) == false && Director::is_cli() == false) {
            return $this->redirectBack();
        }
    }
    
    public function getResponses() {
        
        if ($this->Survey == null) {
            return $this->httpError(404);
        }
        
        $responses = SurveyResponse::get()->filter("SurveyID", $this->Survey->ID);
        
        
        // If 'onlygeo' is passed, non-placed responses are ignored
        if ($this->getRequest()->getVar("onlygeo") !== null) {
            
            $responses = $responses->exclude([
                "Latitude" => 0.0,
                "Longitude" => 0.0
            ]);
        }
        
        
        $data = [];
        
        foreach ($responses as $r) {
            
            $data[] = [
                'id' => $r->ID,
                'surveyId' => $r->SurveyID,
                'memberId' => $r->MemberID,
                'lat' => floatval($r->Latitude),
                'lng' => floatval($r->Longitude),
                'responses' => $r->jsonField('Responses')
            ];
        }
        
        return $this->jsonResponse($data);
    }
    
    public function viewResponse() {
        
        if ($this->Survey == null) {
            return $this->httpError(404);
        }
        
        $response = SurveyResponse::get()
            ->filter('ID', $this->request->param('ResponseID'))
            ->filter('SurveyID', $this->Survey->ID)
            ->first();
        
        if ($response == null) {
            return $this->httpError(404);
        }
        
        
        $rendered = $this->renderWith("SurveyResponse", [
            "Response" => $response
        ]);
        
        return $this->jsonResponse([
            'title' => $response->getTitle(),
            'content' => $rendered->forTemplate()
        ]);
    }
    
    
    
    /*
     *  Utils
     */
    public function postVar($name, &$errors) {
        
        if ($this->request->postVar($name) != null) {
            return $this->request->postVar($name);
        }
        
        $errors[] = "Please provide '$name'";
    }
}
