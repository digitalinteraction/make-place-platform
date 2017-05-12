<?php

/** Media in response to a Survey */
class SurveyMedia extends DataObject {
    
    private static $db = [
        "Name" => "Varchar(255)",
        "Filename" => "Varchar(255)",
        "Path" => "Varchar(255)",
        "Type" => "Varchar(255)",
        "Size" => "Int"
    ];
    
    private static $belongs_many_many = [
        "Responses" => "SurveyResponse"
    ];
}
