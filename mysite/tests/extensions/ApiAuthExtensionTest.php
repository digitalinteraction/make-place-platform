<?php


class MockApiExtController extends ContentController {
    
}


/** Tests ApiAuthExtension */
class ApiAuthExtensionTest extends SapphireTest {
    
    public $usesDatabase = true;
    
    protected $member = null;
    protected $key = null;
    
    public function setUpOnce() {
        parent::setUpOnce();
        
        MockApiExtController::add_extension('ApiAuthExtension');
    }
    
    public function setUp() {
        parent::setUp();
        
        $this->member = Member::create(["Email" => "test@gmail.com"]);
        $this->member->write();
        
        $this->key = ApiKey::create([
            "Key" => "secret",
            "MemberID" => $this->member->ID
        ]);
        $this->key->write();
    }
    
    public function testPass() {
        
        $getVars = ["apikey" => "secret"];
        
        $controller = MockApiExtController::create();
        $controller->setRequest(new SS_HTTPRequest("POST", "localhost", $getVars));
        
        // Auth should pass
        $this->assertTrue($controller->checkApiAuth());
    }
    
    public function testNoKey() {
        
        $getVars = [];
        
        $controller = MockApiExtController::create();
        $controller->setRequest(new SS_HTTPRequest("POST", "localhost", $getVars));
        
        // Auth should fail
        $this->assertFalse($controller->checkApiAuth());
    }
    
    public function testIncorrectKey() {
        
        $getVars = ["apikey" => "bannana"];
        
        $controller = MockApiExtController::create();
        $controller->setRequest(new SS_HTTPRequest("POST", "localhost", $getVars));
        
        // Auth should fail
        $this->assertFalse($controller->checkApiAuth());
    }
    
    public function testMemberIsLoggedIn() {
        
        $getVars = ["apikey" => "secret"];
        
        $controller = MockApiExtController::create();
        $controller->setRequest(new SS_HTTPRequest("POST", "localhost", $getVars));
        
        // Member should be logged in
        $this->assertNotNull(Member::currentUserID());
    }
}
