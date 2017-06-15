<?php

/** ... */
class LoginControllerTest extends FunctionalTest {
    
    protected $controller = null;
    protected $emptyForm = null;
    
    public function setUp() {
        parent::setUp();
        
        // Create a controller for testing
        $this->controller = LoginController::create();
        $this->emptyForm = Form::create($this, "TestForm", FieldList::create(), FieldList::create());
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
            "Email" => "test1@gmail.com"
        ]);
        $member->write();
        
        
        // Create a registration for that user
        $register = Registration::create([
            "Key" => "test-key",
            "MemberID" => $member->ID,
            "Active" => true
        ]);
        $register->write();
        
        
        // Activate the user
        $this->controller->activateEmail("test1@gmail.com", "test-key");
        
        
        // Update the registration
        $register = Registration::get()->byID($register->ID);
        
        
        // Check the registration is now 'Active'
        $this->assertEquals(0, $register->Active);
        
        
        // Check the member now has interaction priverlages
        $this->assertTrue(Permission::check('VERIFIED', 'any', $member));
    }
    
    public function testActivateEmailFailsWhenNotActive() {
        
        // Create a member to register
        $member = Member::create([
            "Email" => "test2@gmail.com"
        ]);
        $member->write();
        
        
        // Create a registration for that user
        $register = Registration::create([
            "Key" => "test-key",
            "MemberID" => $member->ID,
            "Active" => false
        ]);
        $register->write();
        
        
        // Activate the user
        $this->controller->activateEmail("test2@gmail.com", "test-key");
        
        
        // Update the registration
        $register = Registration::get()->byID($register->ID);
        
        
        // Check the registration is now 'Active'
        $this->assertEquals(0, $register->Active);
        
        
        // Check the member now has interaction priverlages
        $this->assertFalse(Permission::check('VERIFIED', 'any', $member));
    }
    
    public function testRegistered() {
        
        // Check it returns something to render
        $this->assertNotNull($this->controller->registered());
    }
    
    
    
    
    public function testRegisterForm() {
        
        // Check a form was created
        $this->assertNotNull($this->controller->RegisterForm());
    }
    
    public function testSubmitRegisterCreatesAMember() {
        
        $this->controller->submitRegister([
            "FirstName" => "Test",
            "Surname" => "User",
            "Email" => "test3@gmail.com",
            "Password" => "password"
        ], $this->emptyForm);
        
        
        // Should create a new Member record
        $this->assertEquals(1, Member::get()->filter("Email", "test3@gmail.com")->count());
    }
    
    public function testSubmitRegisterCreatesARegistration() {
        
        $this->controller->submitRegister([
            "FirstName" => "Test",
            "Surname" => "User",
            "Email" => "test4@gmail.com",
            "Password" => "password"
        ], $this->emptyForm);
        
        
        // Should create a Registration record
        $this->assertEquals(1, Registration::get()->filter("Member.Email", "test4@gmail.com")->count());
    }
    
    public function testSubmitRegisterSendsAnEmail() {
        
        $this->controller->submitRegister([
            "FirstName" => "Test",
            "Surname" => "User",
            "Email" => "test5@gmail.com",
            "Password" => "password"
        ], $this->emptyForm);
        
        
        // Should send an email
        $this->assertEmailSent("test5@gmail.com");
    }
    
    public function testSubmitRegisterWithExistingMember() {
        
        // Create an existing member
        Member::create([
            "Email" => "test6@gmail.com",
            "FirstName" => "Some",
            "Surname" => "Name",
        ])->write();
        
        
        $this->controller->submitRegister([
            "FirstName" => "Test",
            "Surname" => "User",
            "Email" => "test6@gmail.com",
            "Password" => "password"
        ], $this->emptyForm);
        
        $member = Member::get()->filter("Email", "test6@gmail.com")->first();
        
        // Should Update the member fields
        $this->assertEquals("Test", $member->FirstName);
        $this->assertEquals("User", $member->Surname);
        
        
        // Should send an email
        $this->assertEmailSent("test6@gmail.com");
    }
    
    public function testSubmitRegisterDoesNothingWithAnValidMember() {
        
        // Create a registered member
        $member = Member::create([
            "Email" => "test7@gmail.com",
            "FirstName" => "Some",
            "Surname" => "Name",
        ]);
        $member->write();
        $member->addInteraction();
        
        
        // Try to register as that user
        $this->controller->submitRegister([
            "FirstName" => "Test",
            "Surname" => "User",
            "Email" => "test7@gmail.com",
            "Password" => "password"
        ], $this->emptyForm);
        
        
        // Check it didn't send them an email
        $this->assertFalse((bool)$this->findEmail("test7@gmail.com"));
    }
    
    public function testSubmitRegisterDeactivatesPreviousAttempts() {
        
        // Create a member that has already tried to register
        $member = Member::create([
            "Email" => "test8@gmail.com"
        ]);
        $member->write();
        
        Registration::create([
            "MemberID" => $member->ID,
            "Active" => true
        ])->write();
        
        // Try to register them again
        $this->controller->submitRegister([
            "FirstName" => "Test",
            "Surname" => "User",
            "Email" => "test8@gmail.com",
            "Password" => "password"
        ], $this->emptyForm);
        
        
        // Find the active registrations for them
        $registrations = Registration::get()->filter([
            "MemberID" => $member->ID,
            "Active" => true
        ])->count();
        
        
        // Check only 1 is active
        $this->assertEquals(1, $registrations);
    }
    
    
    
    
    public function testLoginForm() {
        
        // Check a form was created
        $this->assertNotNull($this->controller->LoginForm());
    }
    
    public function testSubmitLogin() {
        
        // ...
    }
    
}
