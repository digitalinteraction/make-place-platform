<?php

class MockCommentableObject extends DataObject {
    private static $extensions = [ "Commentable" ];
}

/** Tests Commentable */
class CommentableTest extends SapphireTest {
    
    public function testCanViewCallsOwner() {
        
        $object = MockCommentableObject::create();
        $canView = $object->canViewComments(null);
        $this->assertFalse($canView);
    }
    
    public function testCanCreateCallsOwner() {
        
        $object = MockCommentableObject::create();
        $canCreate = $object->canCreateComment(null);
        $this->assertFalse($canCreate);
    }
}
