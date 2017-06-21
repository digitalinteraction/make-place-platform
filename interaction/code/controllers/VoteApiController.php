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
    
    
    
    /**
     * @api {get} api/vote/:targetType/:targetId Index
     * @apiName VoteIndex
     * @apiGroup Vote
     *
     * @apiDescription Gets the votes on a record of type 'targetType'
     *
     * @apiParam {string} targetType The classname of the record being voted on
     * @apiParam {string} targetId The id of the record being voted on
     *
     * @apiSuccessExample {json} 200 OK
     * [
     *   {
     *     "id": 344,
     *     "created": "2017-06-19 13:37:59",
     *     "value": 4,
     *     "latest": 1,
     *     "memberID": 1,
     *     "targetID": 24,
     *     "targetClass": "SurveyResponse"
     *   }
     * ]
     */
    public function voteIndex($target) {
        
        // Get the latest votes on that object
        $votes = Vote::get()->filter([
            "TargetClass" => $target->ClassName,
            "TargetID" => $target->ID,
            "Latest" => true
        ]);
        
        // Serialse them into json
        $json = [];
        foreach($votes as $vote) {
            $json[] = $vote->jsonSerialize();
        }
        
        // Return the json
        return $this->jsonResponse($json);
    }
    
    /**
     * @api {post} api/vote/:targetType/:targetId Create
     * @apiName VoteCreate
     * @apiGroup Vote
     * @apiPermission Member
     *
     * @apiDescription adds a vote on a record of type 'targetType'
     *
     * @apiParam {string} targetType The classname of the record being voted on
     * @apiParam {string} targetId The id of the record being voted on
     * @apiParam {int} value The value of the vote, validating depending on that record's vote type
     *
     * @apiSuccessExample {json} 200 OK
     * {
     *   "id": 21,
     *   "created": "2017-06-20 20:43:44",
     *   "value": 1,
     *   "latest": true,
     *   "memberID": 1,
     *   "targetID": 31,
     *   "targetClass": "SurveyResponse"
     * }
     */
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
        if ($target->checkValue($value, $errors) == false) {
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
    
    
    /**
     * @api {get} api/vote/:targetType/:targetId/current Current
     * @apiName VoteCurrent
     * @apiGroup Vote
     * @apiPermission Member
     *
     * @apiDescription Gets the vote the current user has made on this record
     *
     * @apiParam {string} targetType The classname of the record being voted on
     * @apiParam {string} targetId The id of the record being voted on
     *
     * @apiSuccessExample {json} 200 OK
     * {
     *   "value": 1
     * }
     */
    public function currentVote() {
        
        // Get target using the type & id in the url
        $errors = [];
        $target = $this->findObject(
            $this->request->param('TargetType'),
            $this->request->param('TargetID'),
            $errors
        );
        
        
        // Make sure the client is using GET
        if ($this->request->httpMethod() != "GET") {
            $errors[] = "Please use GET";
        }
        
        
        // Return any errors found
        if (count($errors)) {
            return $this->jsonResponse($errors, 400);
        }
        
        
        // Check the client is logged in
        if (Member::currentUserID() == null) {
            return $this->jsonAuthError();
        }
        
        
        // Fetch the latest vote for the current member
        $vote = Vote::get()->filter([
            "TargetClass" => $target->ClassName,
            "TargetID" => $target->ID,
            "Latest" => true,
            "MemberID" => Member::currentUserID()
        ])->first();
        
        
        // Return the value of the vote or 0 if they haven't voted
        return $this->jsonResponse([
            "value" => $vote == null ? 0 : $vote->Value
        ]);
    }
    
    
}
