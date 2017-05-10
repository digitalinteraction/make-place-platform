<?php

/** Tests ApiKey */
class ApiKeyTest extends FunctionalTest {
    
    public $usesDatabase = true;
    
    protected $member = null;
    
    public function setUp() {
        parent::setUp();
        
        $this->member = Member::create([
            "Email" => "test@gmail.com"
        ]);
        $this->member->write();
        $this->logInAs($this->member);
    }
    
    public function testGetCMSFieldsWhileCreating() {
        
        $apikey = ApiKey::create();
        $fields = $apikey->getCMSFields();
        
        // Check the current member iD was inserted into the form
        $memberValue = $fields->fieldByName("Root.Main.MemberID")->Value();
        $this->assertEquals($this->member->ID, $memberValue);
        
        // Check the key field was removed
        $this->assertNull($fields->fieldByName("Root.Main.Key"));
    }
    
    public function testGetCMSFieldsWhileEditing() {
        
        $apikey = ApiKey::create();
        $apikey->write();
        $fields = $apikey->getCMSFields();
        
        // Check the key field was added
        $this->assertNotNull($fields->fieldByName("Root.Main.Key"));
        
        // Check the member field was removed
        $this->assertNull($fields->fieldByName("Root.Main.MemberID"));
        
        // Check the user field was added to show the owner
        $this->assertNotNull($fields->fieldByName("Root.Main.User"));
    }
    
    public function testKeyGeneration() {
        
        $apikey = ApiKey::create();
        $apikey->MemberID = $this->member->ID;
        $apikey->onBeforeWrite();
        
        // Check a key was generated
        $this->assertNotNull($apikey->Key);
    }
}
