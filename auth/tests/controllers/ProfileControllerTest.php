<?php

/** Tests ProfileController */
class ProfileControllerTest extends SapphireTest {
    
    /** @var ProfileController */
    protected $controller = null;
    
    /** @var Member */
    protected $member = null;
    
    public function setUp() {
        parent::setUp();
        
        $this->controller = ProfileController::create();
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
        
        $this->assertEquals("me/test", $link);
    }
    
    public function testLayout() {
        
        $this->logInWithPermission();
        
        $this->controller->init();
        
        $this->assertNotNull($this->controller->Layout());
    }
    
}
