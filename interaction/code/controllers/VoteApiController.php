<?php

/** A controller to handle actions around voting */
class VoteApiController extends ApiController {
    
    private static $extensions = [];
    
    private static $allowed_actions = [
        "vote", "currentVote"
    ];
    
    private static $url_handlers = [
        'on/$TargetType/$TargetID/current' => "currentVote",
        'on/$TargetType/$TargetID' => "vote"
    ];
    
    
    
    public function vote() {
        
        $errors = [];
        $target = $this->findObject(
            $this->request->param("TargetType"),
            $this->request->param("TargetID"),
            $errors
        );
        
        // Check the target is Votable
        if ($target && $target->hasExtension("Votable") == false) {
            $errors[] = "Cannot vote on {$target->ClassName}";
        }
        
        // Return errors if there were any
        if (count($errors)) {
            return $this->jsonResponse($errors, 400);
        }
        
        if ($this->request->httpMethod() == "POST") {
            return $this->voteCreate($target);
        }
        else {
            return $this->voteIndex($target);
        }
    }
    
    
    
    
    public function voteIndex($target) {
        
        $votes = Vote::get()->filter([
            "TargetClass" => $target->ClassName,
            "TargetID" => $target->ID,
            "Latest" => true
        ]);
        
        $json = [];
        foreach($votes as $vote) {
            $json[] = $vote->jsonSerialize();
        }
        
        return $this->jsonResponse($json);
    }
    
    public function voteCreate($target) {
        
        // Check the request is authenticated
        $this->checkApiAuth();
        $member = Member::currentUser();
        if (!$member) {
            return $this->jsonResponse(["You need to log in to do that"], 401);
        }
        
        
        // Check request parameters
        $errors = [];
        $value = $this->bodyVar("value", $errors);
        if ($value && $target->checkValue($value, $errors) == false) {
            $errors[] = "Invalid vote $value";
        }
        
        
        // If there were any errors, returnt them
        if (count($errors)) { return $this->jsonResponse($errors, 400); }
        
        
        // Mark previous votes to Latest=0
        $oldVotes = Vote::get()->filter([
            "Latest" => true,
            "MemberID" => $member->ID,
            "TargetID" => $target->ID,
            "TargetClass" => $target->ClassName
        ]);
        
        // Update each vote
        foreach ($oldVotes as $vote) {
            $vote->Latest = false;
            $vote->write();
        }
        
        
        // Create the vote
        $vote = Vote::create([
            "Value" => $value,
            "Latest" => true,
            "MemberID" => $member->ID,
            "TargetID" => $target->ID,
            "TargetClass" => $target->ClassName
        ]);
        
        
        // Save the vote
        $vote->write();
        
        
        // Return the new vote
        return $this->jsonResponse($vote->jsonSerialize());
    }
    
    public function currentVote() {
        
        return $this->jsonResponse("Current Vote");
    }
    
    
}
