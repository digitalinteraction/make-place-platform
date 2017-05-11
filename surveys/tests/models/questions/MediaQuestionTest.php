<?php

/** Tests MediaQuestion */
class MediaQuestionTest extends SapphireTest {
    
    public $usesDatabase = true;
    protected $question = null;
    
    public function setUp() {
        parent::setUp();
        
        $this->question = MediaQuestion::create([
            "Name" => "Media Question",
            "Handle" => "media-question",
            "MediaType" => "any",
            "Strategy" => "LOCAL"
        ]);
    }
    
    public function testExtraFields() {
        
        $extras = $this->question->extraFields();
        $this->assertEquals(2, count($extras));
    }
    
    
    /* Value Validation */
    public function testValidateValueWithMediaId() {
        
        $media = SurveyMedia::create([]);
        $media->write();
        
        $errors = $this->question->validateValue($media->ID);
        
        $this->assertEquals(0, count($errors));
    }
    
    public function testValidateValueWithInvalidMediaId() {
        
        $errors = $this->question->validateValue(1);
        
        $this->assertEquals(1, count($errors));
    }
    
    public function testValidateValueWithFile() {
        
        $value = [
            "name" => "test.png",
            "type" => "image/png",
            "tmp_name" => "/tmp/my_image",
            "size" => 12345
        ];
        
        $errors = $this->question->validateValue($value);
        
        $this->assertEquals(0, count($errors));
    }
    
    public function testValidateValueWithInvalidFile() {
        
        $errors = $this->question->validateValue([]);
        
        $this->assertEquals(4, count($errors));
    }
    
    /* Value Packing */
    public function testPackValueWithMediaId() {
        
        $packed = $this->question->packValue("1");
        
        $this->assertEquals("1", $packed);
    }
    
    
    /* Value Unpacking */
    public function testUnpackValue() {
        
        $media = SurveyMedia::create();
        $media->write();
        
        $unpacked = $this->question->unpackValue($media->ID);
        
        $this->assertNotNull($unpacked);
    }
}
