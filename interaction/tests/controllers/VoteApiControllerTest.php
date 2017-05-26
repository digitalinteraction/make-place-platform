<?php



class MockVoteTarget extends DataObject {
    private static $extensions = [ "Votable" ];
    
    public function canViewVotes($member) { return true; }
    public function canCreateVote($member) { return true; }
}

class MockVoteTargetWithPerms extends DataObject {
    private static $extensions = [ "Votable" ];
    
    public function canViewVotes($member) { return false; }
    public function canCreateVote($member) { return false; }
}

class MockNonVotable extends DataObject { }



/** Tests VoteApiController */
/** @group whitelist */
class VoteApiControllerTest extends FunctionalTest {
    
    public $apiBase = "api/vote";
    public $usesDatabase = true;
    
    /** @var MockVoteTarget */
    public $target = null;
    
    
    /* Test Lifecycle */
    public function setUp() {
        parent::setUp();
        
        $this->target = MockVoteTarget::create();
        $this->target->write();
        
        $this->member = Member::create(["Email" => "test@comment.api.controller"]);
        $this->member->write();
    }
    
    
    
    /* Utils */
    function addVoteToTarget($value, $latest = true, $memberID = null) {
        $vote = Vote::create([
            "TargetClass" => $this->target->ClassName,
            "TargetID" => $this->target->ID,
            "Value" => $value,
            "Latest" => $latest,
            "MemberID" => $memberID
        ]);
        
        $vote->write();
        
        return $vote;
    }
    
    
    
    /* Routes Test */
    public function testVoteIndexRoute() {
        
        $res = $this->get("{$this->apiBase}/on/MockVoteTarget/1");
        $this->assertNotEquals(404, $res->getStatusCode());
    }
    
    public function testVoteCreateRoute() {
        
        $res = $this->post("{$this->apiBase}/on/MockVoteTarget/1", []);
        $this->assertNotEquals(404, $res->getStatusCode());
    }
    
    public function testVoteCurrentRoute() {
        
        $res = $this->get("{$this->apiBase}/on/MockVoteTarget/1/current");
        $this->assertNotEquals(404, $res->getStatusCode());
    }
    
    
    
    /* Vote Index Tests */
    public function testVoteIndex() {
        
        $this->addVoteToTarget(1);
        $this->addVoteToTarget(1);
        $this->addVoteToTarget(-1);
        
        $res = $this->get("{$this->apiBase}/on/MockVoteTarget/1");
        
        $json = json_decode($res->getBody(), true);
        
        $this->assertCount(3, $json);
    }
    
    public function testVoteIndexIsOnlyLatest() {
        
        $this->addVoteToTarget(1);
        $this->addVoteToTarget(1, false);
        $this->addVoteToTarget(-1);
        
        $res = $this->get("{$this->apiBase}/on/MockVoteTarget/1");
        
        $json = json_decode($res->getBody(), true);
        
        $this->assertCount(2, $json);
    }
    
    public function testVoteIndexWithNonVotable() {
        
        MockNonVotable::create()->write();
        
        $res = $this->get("{$this->apiBase}/on/MockNonVotable/1");
        
        $this->assertEquals(400, $res->getStatusCode());
    }
    
    public function testVoteIndexWithInvalidTarget() {
        
        $res = $this->get("{$this->apiBase}/on/MockVoteTarget/999");
        
        $this->assertEquals(400, $res->getStatusCode());
    }
    
    
    /* Vote Create Tests */
    public function testVoteCreate() {
        
        $this->member->logIn();
        
        $res = $this->post("{$this->apiBase}/on/MockVoteTarget/1", [
            "value" => 1
        ]);
        
        $this->assertEquals(200, $res->getStatusCode());
    }
    
    public function testVoteCreateUpdatesOldVotes() {
        
        $this->member->logIn();
        
        $vote = $this->addVoteToTarget(1, true, $this->member->ID);
        
        $res = $this->post("{$this->apiBase}/on/MockVoteTarget/1", [
            "value" => 1
        ]);
        
        $oldVote = Vote::get()->byID($vote->ID);
        
        $this->assertFalse((bool)$oldVote->Latest);
    }
}
