<?php

/** A comment on something in the database */
class Comment extends DataObject {
    
    private static $db = [
        "Message" => "Varchar(255)",
        "Deleted" => "Datetime"
    ];
    
    private static $has_one = [
        "Member" => "Member",
        "Target" => "DataObject",
        "Parent" => "Comment",
        "Media" => "File"
    ];
    
    private static $defaults = [
        "Deleted" => false,
        "ParentID" => null
    ];
    
    private static $excluded_fields = [ "none" ];
    
    
    
    public function customiseJson($json) {
        
        // Fetch & nest the member
        $json["member"] = $this->Member()->jsonSerialize();
        unset($json["memberID"]);
        
        // Fetch & nest associated vote
        $vote = Vote::get()->filter([
            "TargetClass" => $this->TargetClassName,
            "TargetID" => $this->TargetID,
            "MemberID" => $this->MemberID,
            "Latest" => true
        ])->first();
        $json["vote"] = $vote != null ? $vote->Value : 0;
        
        return $json;
    }
}
