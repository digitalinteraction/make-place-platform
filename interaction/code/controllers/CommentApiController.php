<?php

/** A controller to handle actions around commenting */
class CommentApiController extends ApiController {
    
    private static $extensions = [];
    
    private static $allowed_actions = [
        "commentChildren", "comment"
    ];
    
    private static $url_handlers = [
        '$CommentID/children' => "commentChildren",
        'on/$TargetType/$TargetID' => "comment"
    ];
    
    
    public function init() {
        parent::init();
        
        // ...
        
        return $this;
    }
    
    public function index() {
        
        return "!?";
    }
    
    /** Entry point for comment endpoints (GET & POST) */
    public function comment() {
        
        // Fetch the target using named parameters
        $errors = [];
        $target = $this->findObject(
            $this->request->param('TargetType'),
            $this->request->param('TargetID'),
            $errors
        );
        
        // Check the target is Commentable
        if ($target && $target->hasExtension("Commentable") == false) {
            $errors[] = "Cannot comment on {$target->ClassName}";
        }
        
        // Fail if there was errors
        if (count($errors)) {
            return $this->jsonResponse($errors, 400);
        }
        
        // Proxy the request depending on the method
        if ($this->request->httpMethod() == "POST") {
            return $this->commentCreate($target);
        }
        else {
            return $this->commentIndex($target);
        }
    }
    
    
    
    /**
     * @api {get} api/comment/:id/children/ Children
     * @apiName CommentChildren
     * @apiGroup Comment
     * @apiPermission Member
     *
     * @apiDescription Gets the children of a comment
     *
     * @apiParam {int} id The id of the comment to get children of
     *
     * @apiSuccessExample {json} 200 OK
     * [
     *   {
     *     "id": 1,
     *     "created": "2017-06-20 20:00:00",
     *     "message": "Test!",
     *     "deleted": false,
     *     "targetID": 42,
     *     "targetClass": "SurveyResponse",
     *     "parentID": 11,
     *     "member": {
     *       "id": 1,
     *       "created": "2017-04-06 16:03:33",
     *       "firstName": "Geoff",
     *       "surname": "Testington",
     *       "profileImageID": 1337
     *     },
     *     "vote": 0
     *   }
     * ]
     */
    public function commentChildren() {
        
        // Fetch children comments
        $children = Comment::get()->filter([
            "ParentID" => $this->request->param("CommentID")
        ]);
        
        // Format the comments
        $json = [];
        foreach ($children as $comment) {
            $json[] = $comment->jsonSerialize();
        }
        
        // Return the comments
        return $this->jsonResponse($json);
    }
    
    
    
    
    /**
     * @api {get} api/comment/on/:targetType/:targetId/ Index
     * @apiName CommentIndex
     * @apiGroup Comment
     *
     * @apiDescription Gets the root level comments on a record of type 'targetType'
     *
     * @apiParam {string} targetType The classname of the record being commented on
     * @apiParam {int} targetId The id of the record being commented on
     *
     * @apiSuccessExample {json} 200 OK
     * [
     *   {
     *     "id": 1,
     *     "created": "2017-06-20 20:00:00",
     *     "message": "Test!",
     *     "deleted": false,
     *     "targetID": 42,
     *     "targetClass": "SurveyResponse",
     *     "parentID": null,
     *     "member": {
     *       "id": 1,
     *       "created": "2017-04-06 16:03:33",
     *       "firstName": "Geoff",
     *       "surname": "Testington",
     *       "profileImageID": 1337
     *     },
     *     "vote": 0
     *   }
     * ]
     */
    public function commentIndex($target) {
        
        // Fetch root level comments
        $comments = Comment::get()->filter([
            "TargetClass" => $target->ClassName,
            "TargetID" => $target->ID
        ])->sort("Created DESC");
        
        // Format the comments as json
        $json = [];
        foreach($comments as $comment) {
            $json[] = $comment->jsonSerialize();
        }
        
        // Return the json comments
        return $this->jsonResponse($json);
    }
    
    /**
     * @api {post} api/comment/on/:targetType/$targetId/ Create
     * @apiName CommentCreate
     * @apiGroup Comment
     * @apiPermission Member
     *
     * @apiDescription Adds a comment on a record of type 'targetType'
     *
     * @apiParam {string} targetType The classname of the thing being commented on
     * @apiParam {int} id The id of the record being commented on
     * @apiParam {string} message The message of the comment being made
     *
     * @apiSuccessExample {json} 200 OK
     * {
     *   "id": 26,
     *   "created": "2017-06-20 20:30:19",
     *   "message": "Test!",
     *   "deleted": null,
     *   "targetID": 31,
     *   "targetClass": "SurveyResponse",
     *   "parentID": null,
     *   "member": {
     *     "id": 1,
     *     "created": "2017-04-06 16:03:33",
     *     "firstName": "Rob",
     *     "surname": "Anderson",
     *     "profileImageID": 4
     *   },
     *   "vote": 0
     * }
     */
    public function commentCreate($target) {
        
        // Check they are logged in
        $this->checkApiAuth();
        $member = Member::currentUser();
        if (!$member) {
            return $this->jsonAuthError();
        }
        
        // Check parameters were passed
        $errors = [];
        $message = $this->bodyVar("message", $errors);
        $parentId = $this->bodyVar("parentID");
        
        // Check the target lets comments
        if (!$target->canCreateComment($member)) {
            $errors[] = "Sorry, you can't do that";
        }
        
        // If a parent is set, check it exists
        if ($parentId != null && Comment::get()->byID($parentId) == null) {
            $errors[] = "Parent comment does not exist";
        }
        
        // If anything went wrong, return the errors
        if (count($errors)) { return $this->jsonResponse($errors, 400); }
        
        
        // Create the comment
        $comment = Comment::create([
            "Message" => trim($message),
            "TargetClass" => $target->ClassName,
            "TargetID" => $target->ID,
            "MemberID" => Member::currentUserID(),
            "ParentID" => $parentId
        ]);
        
        
        // Save the comment
        $comment->write();
        
        
        // Return the serialized comment
        return $this->jsonResponse($comment->jsonSerialize());
    }
    
}
