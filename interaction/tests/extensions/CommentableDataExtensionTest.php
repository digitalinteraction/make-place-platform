<?php

class MockCommentableObject extends DataObject {
    private static $extensions = [ "CommentableDataExtension" ];
    public function canViewComments($member) { return true; }
    public function canCreateComment($member) { return true; }
}

/** Tests CommentableDataExtension */
class CommentableDataExtensionTest extends SapphireTest {
    
    public function testCanViewCallsOwner() {
        
        $object = MockCommentableObject::create();
        $canView = $object->canViewComments(null);
        $this->assertTrue($canView);
    }
    
    public function testCanCreateCallsOwner() {
        
        $object = MockCommentableObject::create();
        $canCreate = $object->canCreateComment(null);
        $this->assertTrue($canCreate);
    }
}
