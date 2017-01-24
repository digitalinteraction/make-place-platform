<?php
/*
 *
 */

class MockQuestion extends Question {
    
    public function extraFields() {
        return [
            TextField::create('TestField', 'TestField')
        ];
    }
}

class SomeComplexMockQuestion extends Question {
    
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
    
    public function testExtraFields() {
        
        // Create a test question
        $q1 = MockQuestion::create(array("Name", "Question"));
        
        // Set the id to mock an init
        $q1->ID = 1;
        
        // Get the fields
        $fields = $q1->getCMSFields();
        
        // Find the field that should have been added
        $extraField = $fields->fieldByName('Root.Main.TestField');
        
        // Check the field was added
        $this->assertNotNull($extraField, "Extrafields not added");
    }
    
    public function testExtraFieldsWithoutInit() {
        
        // Create a test question
        $q1 = MockQuestion::create(array("Name", "Question"));
        
        // Get the fields
        $fields = $q1->getCMSFields();
        
        // Find if the field was added
        $extraField = $fields->fieldByName('Root.Main.TestField');
        
        // Check the field wasn't added
        $this->assertNull($extraField, "Extrafields was added");
    }
    
    public function testFriendlyNames() {
        
        $q1 = Question::create(array("Name", "Question"));
        
        $types = $q1->availableTypes();
        
        $this->assertTrue(in_array("Mock", $types), "Question didn't format the ClassName");
    }
    
    public function testFriendlyNamesSpaces() {
        
        $q1 = Question::create(array("Name", "Question"));
        
        $types = $q1->availableTypes();
        
        $this->assertTrue(in_array("Some Complex Mock", $types), "Question didn't add spaces to ClassName");
    }
    
}
