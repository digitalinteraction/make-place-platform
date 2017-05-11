<?php

/** Tests MediaQuestion */
/** @group whitelist */
class MediaQuestionTest extends SapphireTest {
    
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
        
        $this->assertEquals(1, count($extras));
    }
    
    public function testValidateValue() {
        // ...
    }
    
    public function testPackValue() {
        // ...
    }
    
    public function testUnpackValue() {
        // ...
    }
}
