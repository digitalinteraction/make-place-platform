<?php

/** A DataObject that can be voted on */
class Votable extends DataExtension {
    
    private static $has_many = [
        "Votes" => "Vote.Target"
    ];
    
    /**
     * The different ways which things can be voted on
     *
     * BASIC - An agree or disagree
     * ARROW - An up or down vote
     * EMOJI - An emoji reaction
     *
     * @return [type] [description]
     */
    public static $voting_types = [
        "BASIC" => "Agree/Disagree",
        "ARROW" => "Up/Down Arrows",
        "EMOJI" => "Emoji",
        // "HEART" => "Favourite"
    ];
    
    
    /** The type of voting allowed on this thing, used to validate vote values */
    public function voteType() { return "BASIC"; }
    
    /** Whether a member can view votes on this thing */
    public function canViewVotes($member = null) { return false; }
    
    /** Whether a member can cast a vote on this thing */
    public function canCreateVote($member = null) { return false; }
    
    /** Checks if a vote value is allowed */
    public function checkValue($value) {
        
        if ($this->owner->voteType() == "BASIC") {
            return abs($value) < 2;
        }
        
        // FIXME: for now allow any vote for emojis
        if ($this->owner->voteType() == "EMOJI") {
          return true;
        }
        
        return false;
    }
}
