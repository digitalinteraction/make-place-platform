<?php

/** A comment on something in the database */
class Comment extends DataObject {
    
    private static $db = [
        "Message" => "Varchar(255)",
        "Deleted" => "Boolean"
    ];
    
    private static $has_one = [
        "Member" => "Member",
        "Target" => "DataObject",
        "Parent" => "Comment",
        "Media" => "File"
    ];
}
