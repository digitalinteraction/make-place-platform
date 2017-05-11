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
    
    protected $extraClasses = ['new-class'];
}

class SomeComplexMockQuestion extends Question {
    
}

/** Tests Question */
/** @group whitelist */
class QuestionTest extends SapphireTest {
    
    protected $usesDatabase = true;
    protected $question = null;
    
    
    /* Testing lifecycle */
    public function setUp() {
        
        parent::setUp();
        
        $this->question = MockQuestion::create(["Name", "Question"]);
    }
    
    
    
    /* Test Handle */
    public function testHandleGeneration() {
        
        $question = Question::create([
            "Name" => "My Fancy Question"
        ]);
        
        $question->write();
        
        $this->assertEquals("my-fancy-question", $question->Handle);
    }
    
    public function testHandleWhenItsAlreadySet() {
        
        $question = MockQuestion::create(["Name" => "Question"]);
        
        $survey = Survey::create(["Name" => "Survey"]);
        $survey->write();
        
        
        $question->SurveyID = $survey->ID;
        
        $question->write();
        $first = $question->Handle;
        
        $question->write();
        $second = $question->Handle;
        
        
        // Test the handle didn't change
        $this->assertEquals($first, $second);
    }
    
    public function testHandleIsUnique() {
        
        $q1 = Question::create(["Name" => "Question"]);
        $q2 = Question::create(["Name" => "Question"]);
        
        $survey = Survey::create(["Name" => "Survey"]);
        $survey->write();
        
        $q1->SurveyID = $survey->ID;
        $q2->SurveyID = $survey->ID;
        
        $q1->write();
        $q2->write();
        
        $this->assertNotEquals($q1->Handle, $q2->Handle);
    }
    
    
    
    /* Test types field */
    public function testTypeField() {
        
        $question = Question::create(["Name" => "Question"]);
        
        $types = $question->availableTypes();
        
        $this->assertTrue(count($types) > 0);
    }
    
    public function testFriendlyNames() {
        
        $types = $this->question->availableTypes();
        
        $this->assertTrue(in_array("Mock", $types), "Question didn't format the ClassName");
    }
    
    public function testFriendlyNamesSpaces() {
        
        $types = Question::create()->availableTypes();
        
        $this->assertTrue(in_array("Some Complex Mock", $types), "Question didn't add spaces to ClassName");
    }
    
    
    
    /* Test extra fields */
    public function testExtraFieldsDefault() {
        
        $question = Question::create(["Name", "Question"]);
        $this->assertEquals(0, count($question->extraFields()));
    }
    
    public function testExtraFields() {
        
        // Set the id to mock an init
        $this->question->ID = 1;
        
        // Get the fields
        $fields = $this->question->getCMSFields();
        
        // Find the field that should have been added
        $extraField = $fields->fieldByName('Root.Main.TestField');
        
        // Check the field was added
        $this->assertNotNull($extraField, "Extrafields not added");
    }
    
    public function testExtraFieldsWithoutInit() {
        
        // Get the fields
        $fields = $this->question->getCMSFields();
        
        // Find if the field was added
        $extraField = $fields->fieldByName('Root.Main.TestField');
        
        // Check the field wasn't added
        $this->assertNull($extraField, "Extrafields was added");
    }
    
    
    
    /* Test render */
    public function testDefaultRender() {
        
        $this->assertNotNull(Question::create()->forTemplate());
    }
    
    public function testRendering() {
        
        $this->assertNotNull($this->question->forTemplate());
    }
    
    public function testRenderField() {
        
        $this->assertNotNull($this->question->renderField());
    }
    
    
    
    /* Test Properties */
    public function testDefaultType() {
        
        $this->assertEquals("text", $this->question->getType());
    }
    
    public function testFieldName() {
        
        $this->question->Handle = "test";
        
        $this->assertEquals("Fields[test]", $this->question->getFieldName());
    }
    
    public function testClasses() {
        
        $this->assertEquals('control new-class', $this->question->getClasses());
    }
    
    
    
    /* Test Default Value Handling */
    public function testValidateValue() {
        $this->assertEquals(0, count($this->question->validateValue("value")));
    }
    
    public function testPackValue() {
        $this->assertEquals("value", $this->question->packValue("value"));
    }
    
    public function testUnpackValue() {
        $this->assertEquals("value", $this->question->unpackValue("value"));
    }
    
    
    /* Test Sample value */
    public function testSample() {
        $this->assertEquals(["type" => "MockQuestion"], $this->question->sample());
    }
    
}
