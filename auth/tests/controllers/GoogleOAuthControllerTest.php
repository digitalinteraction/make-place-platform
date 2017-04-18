<?php

/** Testing GoogleOAuthController */
class GoogleOAuthControllerTest extends SapphireTest {
    
    /** @var GoogleOAuthController */
    protected $controller = null;
    
    
    public function setUp() {
        parent::setUp();
        
        $this->controller = GoogleOAuthController::create();
    }
    
    
    
    public function testInit() {
        
        $this->controller->init();
        $this->assertNotNull($this->controller->google);
    }
    
    public function testIndexReturnsA404() {
        
        $this->setExpectedException(SS_HTTPResponse_Exception::class);
        
        $res = $this->controller->index();
    }
    
    public function testLoginReturnsARedirect() {
        
        // ...
        
        
    }
}
