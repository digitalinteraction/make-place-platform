<?php

/** A DataObject that can be commented on */
class CommentableDataExtension extends DataExtension {
    
    private static $has_many = [
        "Comments" => "Comment.Target"
    ];
    
    
    public function canViewComments($member) { return false; }
    
    public function canCreateComment($member) { return false; }
}
