<?php

/** Tests SecureGeneratorExtension */
class SecureGeneratorExtensionTest extends FunctionalTest {
    
    protected $usesDatabase = true;
    
    public function setUp() {
        
        parent::setUp();
        
        $this->member = Member::create([
            "Email" => "test@gmail.com"
        ]);
        $this->member->write();
        
        
        $this->register = Registration::create();
        $this->register->write();
    }
    
    public function testGeneration() {
        
        $key = $this->register->generateUniqueKey($this->member, 'Registration', 'Key');
        
        $this->assertNotNull($key);
    }
}
