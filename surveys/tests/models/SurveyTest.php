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
    
    public function testUniqueHandle() {
        
        $s1 = Survey::create(["Name" => "My Survey"]);
        $s2 = Survey::create(["Name" => "My Survey"]);
        
        $s1->write();
        $s2->write();
        
        $this->assertNotEquals($s1->Handle, $s2->Handle, "Survey's handle aren't unique");
    }
}
