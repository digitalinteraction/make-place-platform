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
        
        
        // Get parameters from the request
        $surveyId = $this->postVar('SurveyID', $errors);
        $userId = Member::currentUserID();
        $fields = $this->postVar('Fields', $errors);
        $token = $this->postVar('SecurityID', $errors);
        
        $lat = $this->postVar('ResponseLat');
        $lng = $this->postVar('ResponseLng');
        
        $redirectBack = $this->postVar('RedirectBack') != null;
        
        // Check there is a user logged in
        if ($userId == null) {
            // $errors[] = "You need to be logged in to do that";
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
        
        
        // Make sure if lat or lng are set, that they are both set
        if (($lat == null && $lng != null) || ($lng == null && $lat != null)) {
            $errors[] = "Please provide both 'ResponseLat' and 'ResponseLng'";
        }
        
        
        // If there is any errors, stop here
        if (count($errors) > 0) {
            
            return $this->jsonResponse($errors, 404);
        }
        
        // Generate a SurveyResponse & save it
        $response = SurveyResponse::create([
            'SurveyID' => $surveyId,
            'MemberID' => $userId,
            'UserID' => $userId,
            'Responses' => $fields,
            'Latitude' => $lat,
            'Longitude' => $lng
        ]);
        
        $response->write();
        
        
        // If not ajax or command-line, redirect back
        // IDEA: refactor to a parameter? e.g. RedirectURL? + check if on site
        if ($redirectBack != null) {
            return $this->redirectBack();
        }
        
        
        return $this->jsonResponse($response->toJson());
    }
    
    public function viewSurvey() {
        
        $lat = $this->getVar('lat');
        $lng = $this->getVar('lng');
        
        if ($lat != null && $lng != null) {
            $this->Survey->ResponseLat = $lat;
            $this->Survey->ResponseLng = $lng;
        }
        
        // TODO: report an error if only 1 is passed?
        
        return $this->jsonResponse([
            'title' => $this->Survey->Name,
            'content' => $this->Survey->forTemplate()->getValue()
        ]);
    }
    
    public function getResponses() {
        
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
        
        
        $rendered = $this->renderWith("SurveyResponse", [
            "Response" => $response
        ]);
        
        // return $this->jsonResponse([
        //     'title' => $response->getTitle(),
        //     'content' => $rendered->getValue()
        // ]);
        
        return $this->jsonResponse($response->toJson());
    }
    
    
    
    /*
     *  Utils
     */
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
