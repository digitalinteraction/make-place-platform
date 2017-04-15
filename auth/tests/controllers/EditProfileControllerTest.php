<?php

/** ... */
class EditProfileControllerTest extends SapphireTest {
    
    /** @var EditProfileController */
    protected $controller = null;
    
    /** @var Member */
    protected $member = null;
    
    public function setUp() {
        parent::setUp();
        
        $this->controller = EditProfileController::create();
    }
    
    
    public function testInitFailsNotLoggedIn() {
        
        $this->setExpectedException(SS_HTTPResponse_Exception::class);
        
        $this->controller->init();
    }
    
    
    public function testInitWithMember() {
        
        $this->logInWithPermission();
        
        $res = $this->controller->init();
        
        $this->assertNull($res);
    }
    
    public function testInitSetsMember() {
        
        $this->logInWithPermission();
        
        $res = $this->controller->init();
        
        $this->assertNotNull($this->controller->Member);
    }
    
    
    
    
    public function testLink() {
        
        $link = $this->controller->Link("test");
        
        $this->assertEquals("me/edit/test", $link);
    }
    
    public function testLayout() {
        
        $this->logInWithPermission();
        
        $this->controller->init();
        
        $this->assertNotNull($this->controller->Layout());
    }
    
    
    public function testMemberEditForm() {
        
        $this->logInWithPermission();
        
        $this->controller->init();
        
        $this->assertNotNull($this->controller->MemberEditForm());
    }
    
    
    
    public function testSubmitMemberEditForm() {
        
        // ...
    }
    
    
    
}
