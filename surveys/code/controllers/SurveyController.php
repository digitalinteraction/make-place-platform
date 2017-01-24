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
        
        // $post = $this->request->postVars();
        // var_dump($post);
        
        // return $this->Survey->Name . ': submit';
        
        // return $this->httpError(404);
    }
}
