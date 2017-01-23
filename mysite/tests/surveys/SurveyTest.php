<?php
/*
 *
 */
class SurveyTest extends SapphireTest {
    
    // ...
    public function setUp() {
        
        parent::setUp();
        
        // ...
    }
    
    public function testInit() {
        
        $this->assertTrue(true);
    }
    
    public function testHandleGeneration() {
        
        $survey = Survey::create([
            "Name" => "My Fancy Survey"
        ]);
        
        $survey->write();
        
        $this->assertEquals("my-fancy-survey", $survey->Handle);
    }
}
