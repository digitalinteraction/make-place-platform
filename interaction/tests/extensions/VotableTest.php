<?php

class MockVotableObject extends DataObject {
    private static $extensions = [ "Votable" ];
}

class TypedMockVotableObject extends DataObject {
    private static $extensions = [ "Votable" ];
    public $type = "BASIC";
    public function voteType() { return $this->type; }
}

/** Tests Votable */
class VotableTest extends SapphireTest {
    
    public function testVoteType() {
        $votable = MockVotableObject::create();
        $this->assertEquals("BASIC", $votable->voteType());
    }
    
    public function testCanViewVotes() {
        $votable = MockVotableObject::create();
        $this->assertFalse($votable->canViewVotes());
    }
    
    public function testCanCreateVote() {
        $votable = MockVotableObject::create();
        $this->assertFalse($votable->canCreateVote());
    }
    
    public function testCheckValue() {
        $typed = TypedMockVotableObject::create();
        
        $this->assertFalse($typed->checkValue(-2));
        $this->assertTrue($typed->checkValue(-1));
        $this->assertTrue($typed->checkValue(0));
        $this->assertTrue($typed->checkValue(+1));
        $this->assertFalse($typed->checkValue(+2));
    }
}
