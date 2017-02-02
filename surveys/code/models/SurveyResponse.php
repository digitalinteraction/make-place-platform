<?php

/** ... */
class SurveyResponse extends DataObject {
    
    private static $db = [
        "Responses" => "JsonText",
        'Latitude' => 'Decimal(16, 8)',
        'Longitude' => 'Decimal(16, 8)',
    ];
    
    private static $has_one = [
        "Member" => "Member",
        "Survey" => "Survey"
    ];
}
