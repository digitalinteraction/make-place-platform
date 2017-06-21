<?php

class MockCommentTarget extends DataObject {
    private static $extensions = [ "Commentable" ];
    
    public function canViewComments($member) { return true; }
    public function canCreateComment($member) { return true; }
}

class MockCommentTargetWithPerms extends DataObject {
    private static $extensions = [ "Commentable" ];
    
    public function canViewComments($member) { return false; }
    public function canCreateComment($member) { return false; }
}

class MockNonCommentable extends DataObject { }



/** Tests CommentApiController */
/** @group whitelist */
class CommentApiControllerTest extends FunctionalTest {
    
    public $usesDatabase = true;
    public $apiBase = "api/comment";
    
    
    /* Test Lifecycle */
    public function setUp() {
        parent::setUp();
        
        $this->target = MockCommentTarget::create();
        $this->target->write();
        
        $this->member = Member::create([
            "Email" => "test@comment.api.controller",
            "FirstName" => "Geoff",
            "Surname" => "Testington"
        ]);
        $this->member->write();
    }
    
    
    /* Setup Tests */
    public function testChildrenRoute() {
        
        $res = $this->get("{$this->apiBase}/1/children");
        
        $this->assertNotEquals(404, $res->getStatusCode());
    }
    
    public function testIndexRoute() {
        
        $res = $this->get("{$this->apiBase}/on/Response/1");
        
        $this->assertNotEquals(404, $res->getStatusCode());
    }
    
    public function testCreateRoute() {
        
        $res = $this->post("{$this->apiBase}/on/Response/1", []);
        
        $this->assertNotEquals(404, $res->getStatusCode());
    }
    
    
    /* Comment Index Tests */
    public function testCommentsIndex() {
        
        Comment::create(["TargetClass" => "MockCommentTarget", "TargetID" => "1"])->write();
        Comment::create(["TargetClass" => "MockCommentTarget", "TargetID" => "1"])->write();
        
        $res = $this->get("{$this->apiBase}/on/MockCommentTarget/1");
        $json = json_decode($res->getBody(), true);
        
        $this->assertEquals(200, $res->getStatusCode());
        $this->assertCount(2, $json);
    }
    
    public function testCommentsIndexWithNonCommentable() {
        
        MockNonCommentable::create()->write();
        
        $res = $this->get("{$this->apiBase}/on/MockNonCommentable/1");
        
        $this->assertEquals(400, $res->getStatusCode());
    }
    
    public function testCommentIndexWithInvalidTarget() {
        
        $res = $this->get("{$this->apiBase}/on/MockCommentTarget/999");
        
        $this->assertEquals(400, $res->getStatusCode());
    }
    
    public function testCommentIndexEmbedsMember() {
        
        Comment::create([
            "TargetClass" => "MockCommentTarget",
            "TargetID" => "1",
            "MemberID" => $this->member->ID
        ])->write();
        
        $res = $this->get("{$this->apiBase}/on/MockCommentTarget/1");
        $json = json_decode($res->getBody(), true);
        
        $this->assertTrue(isset($json[0]["member"]));
    }
    
    public function testCommentIndexEmbedsVote() {
        
        Comment::create([
            "TargetClass" => "MockCommentTarget",
            "TargetID" => "1",
            "MemberID" => $this->member->ID
        ])->write();
        
        Vote::create([
            "Value" => 1,
            "TargetClass" => "MockCommentTarget",
            "TargetID" => "1",
            "MemberID" => $this->member->ID,
            "Latest" => true
        ])->write();
        
        $res = $this->get("{$this->apiBase}/on/MockCommentTarget/1");
        $json = json_decode($res->getBody(), true);
        
        $this->assertTrue(isset($json[0]["vote"]));
        $this->assertEquals($json[0]["vote"], 1);
    }
    
    
    /* Comment Create Tests */
    public function testCreateComment() {
        
        $this->member->logIn();
        
        $res = $this->post("{$this->apiBase}/on/MockCommentTarget/1", [
            "message" => "Test Message"
        ]);
        
        
        $json = json_decode($res->getBody(), true);
        
        $this->assertEquals(200, $res->getStatusCode());
    }
    
    public function testCommentCreateWithoutMessage() {
        
        $this->member->logIn();
        
        $res = $this->post("{$this->apiBase}/on/MockCommentTarget/1", []);
        
        $this->assertEquals(400, $res->getStatusCode());
    }
    
    public function testCommentCreateWithoutAuth() {
        
        $res = $this->post("{$this->apiBase}/on/MockCommentTarget/1", [
            "message" => "Test"
        ]);
        
        $this->assertEquals(401, $res->getStatusCode());
    }
    
    public function testCommentCreateAsksTarget() {
        
        $this->member->logIn();
        
        MockCommentTargetWithPerms::create()->write();
        
        $res = $this->post("{$this->apiBase}/on/MockCommentTargetWithPerms/1", [
            "message" => "Test"
        ]);
        
        $json = json_decode($res->getBody(), true);
        
        $this->assertEquals(400, $res->getStatusCode());
        $this->assertCount(1, $json);
    }
    
    public function testCommentCreateWithInvalidParent() {
        
        $this->member->logIn();
        
        $res = $this->post("{$this->apiBase}/on/MockCommentTarget/1", [
            "message" => "Test",
            "parentID" => 100
        ]);
        
        $json = json_decode($res->getBody(), true);
        
        $this->assertEquals(400, $res->getStatusCode());
        $this->assertCount(1, $json);
    }
    
    
    /* Comment Children */
    public function testCommentChildren() {
        
        Comment::create(["ParentID" => 1])->write();
        Comment::create(["ParentID" => 1])->write();
        Comment::create(["ParentID" => 2])->write();
        
        $res = $this->get("{$this->apiBase}/1/children");
        
        $json = json_decode($res->getBody(), true);
        
        $this->assertCount(2, $json);
    }
    
}
