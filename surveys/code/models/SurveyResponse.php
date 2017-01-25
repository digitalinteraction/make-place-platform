<?php

/** ... */
class SurveyResponse extends DataObject {
    
    private static $db = [
        "Responses" => "JsonText"
    ];
    
    private static $has_one = [
        "Member" => "Member",
        "Survey" => "Survey"
    ];
}
