<?php
/*
 *
 */

class MockQuestion extends Question {
    
}

class QuestionTest extends SapphireTest {
    
    
    public function setUp() {
        
        parent::setUp();
        
        // ...
    }
    
    public function testInit() {
        
        $this->assertTrue(true);
    }
    
    public function testHandleGeneration() {
        
        $question = Question::create([
            "Name" => "My Fancy Question"
        ]);
        
        $question->write();
        
        $this->assertEquals("my-fancy-question", $question->Handle);
    }
    
    public function testTypeField() {
        
        $question = Question::create([
            "Name" => "Test Question"
        ]);
        
        $types = $question->availableTypes();
        
        $this->assertTrue(count($types) > 0);
    }
    
    public function testHandleCheck() {
        
        $q1 = Question::create(array("Name" => "Question"));
        $q2 = Question::create(array("Name" => "Question"));
        
        $survey = Survey::create(array("Name" => "Survey"));
        $survey->write();
        
        $q1->SurveyID = $survey->ID;
        $q2->SurveyID = $survey->ID;
        
        $q1->write();
        $q2->write();
        
        $this->assertNotEquals($q1->Handle, $q2->Handle);
    }
}
