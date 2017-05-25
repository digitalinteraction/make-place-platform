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
    
    public function comment() {
        
        $errors = [];
        $target = $this->getTarget(
            $this->request->param('TargetType'),
            $this->request->param('TargetID'),
            $errors
        );
        
        if (count($errors)) {
            return $this->jsonResponse($errors, 400);
        }
        
        if ($this->request->httpMethod() == "POST") {
            return $this->commentCreate($target);
        }
        else {
            return $this->commentIndex($target);
        }
    }
    
    function getTarget($type, $id, &$errors = []) {
        
        $errorMsg = "$type($id)";
        
        // Check the class exists
        if (!class_exists($type)) {
            $errors[] = "Unknown Target $errorMsg"; return null;
        }
        
        // Reflect the class to check its a DataObject
        $reflection = new ReflectionClass($type);
        if (!$reflection->isSubclassOf('DataObject')) {
            $errors[] = "Invalid Target $errorMsg"; return null;
        }
        
        // Check the object is Commentable
        if (!DataObject::has_extension($type, "CommentableDataExtension")) {
            $errors[] = "Cannot comment on $errorMsg"; return null;
        }
        
        // Fetch the object
        $object = $type::get()->byID($id);
        
        // Check the object exists
        if (!$object) {
            $errors[] = "$errorMsg does not exist"; return null;
        }
        
        // Return the object
        return $object;
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
     * {
     *   "msg": "coming soon"
     * }
     */
    public function commentChildren() {
        
        $children = Comment::get()->filter([
            "ParentID" => $this->request->param("CommentID")
        ]);
        
        $json = [];
        foreach ($children as $comment) {
            $json[] = $comment->jsonSerialize();
        }
        
        return $this->jsonResponse($json);
    }
    
    
    
    
    /**
     * @api {get} api/comment/on/:targetType/$targetId/ Index
     * @apiName CommentIndex
     * @apiGroup Comment
     * @apiPermission Member
     *
     * @apiDescription Gets the root level comments on a record of type 'classname'
     *
     * @apiParam {string} targetType The classname of the thing being commented on
     * @apiParam {int} id The id of the record being commented on
     *
     * @apiSuccessExample {json} 200 OK
     * {
     *   "msg": "coming soon"
     * }
     */
    public function commentIndex($target) {
        
        $comments = Comment::get()->filter([
            "TargetClass" => $target->ClassName,
            "TargetID" => $target->ID
        ]);
        
        $json = [];
        foreach($comments as $comment) {
            $json[] = $comment->jsonSerialize();
        }
        
        return $this->jsonResponse($json);
    }
    
    /**
     * @api {post} api/comment/on/:targetType/$targetId/ Create
     * @apiName CommentCreate
     * @apiGroup Comment
     * @apiPermission Member
     *
     * @apiDescription Adds a comment on a record of type 'classname'
     *
     * @apiParam {string} targetType The classname of the thing being commented on
     * @apiParam {int} id The id of the record being commented on
     * @apiParam {string} message The message of the comment being made
     *
     * @apiSuccessExample {json} 200 OK
     * {
     *   "msg": "coming soon"
     * }
     */
    public function commentCreate($target) {
        
        // Check they are logged in
        $this->checkApiAuth();
        $member = Member::currentUser();
        if (!$member) {
            return $this->jsonResponse(["You need to log in to do that"], 401);
        }
        
        // Check parameters were passed
        $errors = [];
        $message = $this->bodyVar("message", $errors);
        $parentId = $this->bodyVar("parentID");
        if (!$target->canCreateComment($member)) {
            $errors[] = "Sorry, you can't do that";
        }
        
        if ($parentId != null && Comment::get()->byID($parentId) == null) {
            $errors[] = "Parent comment does not exist";
        }
        
        
        if (count($errors)) { return $this->jsonResponse($errors, 400); }
        
        
        // Create the comment
        $comment = Comment::create([
            "Message" => $message,
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
    
    // ...
}
