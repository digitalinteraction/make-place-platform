<?php

class MockRecaptchaField extends RecaptchaField {
    
    public $response = false;
    
    public function recaptchaResponse($value) {
        
        return [
            "success" => (bool)$this->response
        ];
    }
}



/** Tests for RecaptchaField */
class RecaptchaFieldTest extends SapphireTest {
    
    protected $field = null;
    protected $validator = null;
    
    public function setUp() {
        
        parent::setUp();
        
        $this->field = MockRecaptchaField::create("Captcha");
        $this->validator = RequiredFields::create([]);
    }
    
    
    public function testInit() {
        
        $this->assertNotNull($this->field);
        $this->assertNotNull($this->field->PublicKey);
    }
    
    public function testGetPublicKey() {
        
        $this->assertNotNull($this->field->getPublicKey(), G_RECAPTCHA_PUBLIC);
    }
    
    public function testField() {
        
        $this->assertNotNull($this->field->Field());
    }
    
    
    
    public function testValidateWithMissingValue() {
        
        unset($_REQUEST['g-recaptcha-response']);
        
        $this->assertFalse($this->field->validate($this->validator));
    }
    
    public function testValidateFailing() {
        
        $this->field->response = false;
        $_REQUEST['g-recaptcha-response'] = "some-key";
        $this->assertFalse($this->field->validate($this->validator));
    }
    
    public function testValidatePassing() {
        
        $this->field->response = true;
        $_REQUEST['g-recaptcha-response'] = "some-key";
        $this->assertTrue($this->field->validate($this->validator));
    }
}
