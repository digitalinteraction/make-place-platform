<?php

/** A DataObject that can be commented on */
class Commentable extends DataExtension {
    
    private static $has_many = [
        "Comments" => "Comment.Target"
    ];
    
    
    public function canViewComments($member = null) { return false; }
    
    public function canCreateComment($member = null) { return false; }
}
