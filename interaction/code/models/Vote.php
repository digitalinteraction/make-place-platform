<?php

/** A vote on something */
class Vote extends DataObject {
    
    private static $extensions = [ "CommentableDataExtension" ];
    
    private static $db = [
        "Value" => "Int",
        "Latest" => "Boolean",
    ];
    
    private static $has_one = [
        "Member" => "Member",
        "Target" => "DataObject"
    ];
}
