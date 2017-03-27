<?php

/** ... */
class LoginControllerTest extends FunctionalTest {
    
    protected $controller = null;
    
    public function setUp() {
        parent::setUp();
        
        // ...
        $this->controller = LoginController::create();
    }
    
    
    
    public function testInit() {
        
        $this->assertNotNull($this->controller);
    }
    
    public function testIndex() {
        
        $response = $this->get("login");
        
        $this->assertEquals(200, $response->getStatusCode());
    }
    
    
    
    
    public function testLink() {
        
        $this->assertEquals("login/", $this->controller->Link());
    }
    
    public function testLayout() {
        
        $this->assertNotNull($this->controller->Layout());
    }
    
    public function testGetBackURLDefault() {
        
        $url = $this->controller->getBackURL();
        
        $this->assertEquals("home/", $url);
    }
    
    public function testGetTabContent() {
        
        $data = $this->controller->getTabContent();
        
        $this->assertEquals(2, $data->count());
        
        $this->assertEquals("login", $data[0]["ID"]);
        $this->assertEquals("register", $data[1]["ID"]);
    }
    
    
    public function testGetIsSimplePage() {
        
        $this->controller->useBasicPage = true;
        $this->assertTrue($this->controller->getIsSimplePage());
    }
    
    
    
    
    public function testEmailSent() {
        
        $this->assertNotNull($this->controller->emailsent());
        $this->assertTrue($this->controller->getIsSimplePage());
    }
    
    public function testActivateEmail() {
        
        // Create a member to register
        $member = Member::create([
            "Email" => "test@gmail.com"
        ]);
        $member->write();
        
        
        // Create a registration for that user
        $register = Registration::create([
            "Key" => "test-key",
            "MemberID" => $member->ID,
            "Used" => false
        ]);
        $register->write();
        
        
        // Activate the user
        $this->controller->activateEmail("test@gmail.com", "test-key");
        
        
        // Update the registration
        $register = Registration::get()->byID($register->ID);
        
        
        // Check the registration is now 'used'
        $this->assertEquals(1, $register->Used);
        
        
        // Check the member now has interaction priverlages
        $this->assertTrue(Permission::check('CAN_INTERACT', 'any', $member));
        
    }
    
    public function testRegistered() {
        
        // Check it returns something to render
        $this->assertNotNull($this->controller->registered());
    }
    
    
    
    
    public function testRegisterForm() {
        
        // Check a form was created
        $this->assertNotNull($this->controller->RegisterForm());
    }
    
    public function testSubmitRegister() {
        
        // ...
    }
    
    
    
    
    public function testLoginForm() {
        
        // Check a form was created
        $this->assertNotNull($this->controller->LoginForm());
    }
    
    public function testSubmitLogin() {
        
        // ...
    }
    
}
