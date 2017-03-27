<?php
/** A model admin to edit surveys */
class SurveyModelAdmin extends ModelAdmin {
    
    private static $managed_models = [
        'Survey'
    ];
    
    private static $menu_icon = 'surveys/images/survey.png';
    
    private static $url_segment = 'survey';
    
    private static $menu_title = 'Surveys';
}
