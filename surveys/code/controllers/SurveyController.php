<?php

/** ... */
class SurveyController extends Controller {
    
    private static $allowed_actions = [
        'index', 'submitSurvey'
    ];
    
    private static $url_handlers = [
        'index' => 'index',
        'submit' => 'submitSurvey'
    ];
    
    public function init() {
        
        parent::init();
        
        $surveyId = $this->request->param('Survey');
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
        $userId = Member::currentUserId();
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
            'UserID' => $userId,
            'Responses' => $fields,
        ]);
        
        $response->write();
    }
    
    
    
    
    /*
     *  Utils
     */
    protected function postVar($name, &$errors) {
        
        if ($this->request->postVar($name) != null) {
            return $this->request->postVar($name);
        }
        
        $errors[] = "Please provide '$name'";
    }
}
