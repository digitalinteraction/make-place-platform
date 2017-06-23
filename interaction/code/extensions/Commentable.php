<?php

/** A DataObject that can be commented on */
class Commentable extends DataExtension {
    
    private static $has_many = [
        "Comments" => "Comment.Target"
    ];
    
    
    /** If the dataobject lets a member see its comments */
    public function canViewComments($member = null) { return false; }
    
    /** If the dataobject lets a member add comments */
    public function canCreateComment($member = null) { return false; }
}
