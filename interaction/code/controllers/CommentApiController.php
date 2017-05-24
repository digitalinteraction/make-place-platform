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
        
        return $this->jsonResponse([ "msg" => "CommentChildren: Coming soon" ]);
    }
    
    public function comment() {
        
        $type = $this->request->param('TargetType');
        $id = $this->request->param('TargetID');
        
        $method = $this->request->httpMethod();
        
        if ($method == "POST") {
            return $this->commentCreate($type, $id);
        }
        else {
            return $this->commentIndex($type, $id);
        }
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
    public function commentIndex($targetType, $targetId) {
        
        return $this->jsonResponse([ "msg" => "CommentIndex: Coming soon" ]);
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
     *
     * @apiSuccessExample {json} 200 OK
     * {
     *   "msg": "coming soon"
     * }
     */
    public function commentCreate($targetType, $targetId) {
        
        return $this->jsonResponse([ "msg" => "CommentCreate: Coming soon" ]);
    }
    
    // ...
}
