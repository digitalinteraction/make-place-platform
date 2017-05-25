<?php

/** A comment on something in the database */
class Comment extends DataObject {
    
    private static $extensions = [ "CommentableDataExtension" ];
    
    private static $db = [
        "Message" => "Varchar(255)",
        "Deleted" => "Datetime"
    ];
    
    private static $defaults = [
        "Deleted" => null,
        "ParentID" => null
    ];
    
    private static $has_one = [
        "Member" => "Member",
        "Target" => "DataObject",
        "Parent" => "Comment",
        "Media" => "File"
    ];
}
