<?php

/** Tests MemberProfileExtension */
class MemberProfileExtensionTest extends SapphireTest {
    
    /** @var Member */
    protected $member = null;
    
    public function setUp() {
        parent::setUp();
        
        $this->member = Member::get()->byID($this->logInWithPermission());
    }
    
    
    public function testInit() {
        
        $this->assertNotNull($this->member);
    }
    
    
    
    public function testGetProfileImageUrl() {
        
        $expected = "/auth/images/default-profile.png";
        $this->assertEquals($expected, $this->member->getProfileImageUrl());
    }
    
    
    public function testUpdateProfile() {
        
        $params = [
            "FirstName" => "Geoff",
            "Surname" => "Testington"
        ];
        
        $errors = $this->member->updateProfile($params);
        
        
        $this->assertEquals("Geoff", $this->member->FirstName);
        $this->assertEquals("Testington", $this->member->Surname);
        $this->assertEquals(0, count($errors));
    }
    
    public function testUpdateProfileRequiresFirstName() {
        
        $params = [
            "Surname" => "Testington"
        ];
        
        $errors = $this->member->updateProfile($params);
        
        $this->assertEquals(["FirstName"], $errors);
    }
    
    public function testUpdateProfileRequiresSurname() {
        
        $params = [
            "FirstName" => "Geoff"
        ];
        
        $errors = $this->member->updateProfile($params);
        
        $this->assertEquals(["Surname"], $errors);
    }
    
    
    
}
