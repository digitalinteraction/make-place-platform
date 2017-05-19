<?php

/* Tests Survey */
class SurveyTest extends SapphireTest {
    
    public $usesDatabase = true;
    
    /* Testing lifecycle */
    public function setUp() {
        
        parent::setUp();
        
        // ...
    }
    
    public function testInit() {
        
        $this->assertTrue(true);
    }
    
    
    /* Handle Tests */
    public function testHandleGeneration() {
        
        $survey = Survey::create([
            "Name" => "My Fancy Survey"
        ]);
        
        $survey->onBeforeWrite();
        
        $this->assertEquals("my-fancy-survey", $survey->Handle);
    }
    
    public function testUniqueHandle() {
        
        $s1 = Survey::create(["Name" => "My Survey"]);
        $s2 = Survey::create(["Name" => "My Survey"]);
        
        $s1->write();
        $s2->write();
        
        $this->assertNotEquals($s1->Handle, $s2->Handle, "Survey's handle aren't unique");
    }
    
    public function testHandleWhenItsSet() {
        
        $survey = new Survey([
            "Name" => "My Fancy Survey"
        ]);
        
        // Write the survey to generate a handle
        $survey->write();
        $first = $survey->Handle;
        
        
        // Write the survey again
        $survey->write();
        $second = $survey->Handle;
        
        // Test the handle didn't change
        $this->assertEquals($first, $second);
    }
    
    
    
    /* CMS Fields Tests */
    public function testCmsFields() {
        
        $s1 = Survey::create(["Name" => "My Survey"]);
        
        $fields = $s1->getCMSFields();
        
        $this->assertNotNull($fields->fieldByName("Root.Main.Name"));
        $this->assertNotNull($fields->fieldByName("Root.Main.Handle"));
        $this->assertNotNull($fields->fieldByName("Root.Main.SubmitTitle"));
        $this->assertNotNull($fields->fieldByName("Root.Main.ViewAuth"));
        $this->assertNotNull($fields->fieldByName("Root.Main.SubmitAuth"));
        $this->assertNull($fields->fieldByName("Root.Main.Questions"));
    }
    
    public function testCmsFieldsWhenCreated() {
        
        $s1 = Survey::create(["Name" => "My Survey"]);
        $s1->write();
        
        $fields = $s1->getCMSFields();
        
        $this->assertNotNull($fields->fieldByName("Root.Main.Questions"));
    }
    
    
    
    /* Security Token Tests */
    public function testSecurityToken() {
        
        $survey = Survey::create([
            "Name" => "My Fancy Survey"
        ]);
        
        $this->assertNotNull($survey->getSecurityToken());
    }
    
    public function testToggleSecurity() {
        
        $survey = Survey::create([
            "Name" => "My Fancy Survey"
        ]);
        
        $survey->toggleSecurity(false);
        
        $this->assertTrue(is_a($survey->getSecurityToken(), 'NullSecurityToken'));
    }
    
    
    
    /* Misc Tests */
    public function testSurveyUrl() {
        
        $survey = Survey::create([
            "Name" => "My Fancy Survey"
        ]);
        $survey->write();
        
        $this->assertEquals("/survey/{$survey->ID}/submit", $survey->getSurveyUrl());
    }
    
    public function testGenerateData() {
        
        $survey = Survey::create([
            "Name" => "My Fancy Survey"
        ]);
        
        $fields = [
            'field' => 'value'
        ];
        
        $data = $survey->generateData($fields);
        
        $this->assertEquals($fields, $data['Fields']);
    }
    
    public function testQuestionMap() {
        
        // Create a survey with some questions
        $survey = Survey::create(["Name" => "My Survey"]);
        $survey->Questions()->addMany([
            Question::create(["Handle" => "first-question"]),
            Question::create(["Handle" => "second-question"])
        ]);
        
        
        // Generate the map and check it put the questions in
        $map = $survey->getQuestionMap();
        $this->assertArrayHasKey("first-question", $map);
        $this->assertArrayHasKey("second-question", $map);
    }
    
    public function testWithRedirect() {
        
        $survey = Survey::create([
            "Name" => "My Fancy Survey"
        ]);
        
        $survey->WithRedirect();
        
        $this->assertTrue($survey->RedirectBack);
    }
    
    
    
    /* Rendering Tests */
    public function testRender() {
        
        $survey = Survey::create([
            "Name" => "My Fancy Survey"
        ]);
        
        $this->assertNotNull($survey->forTemplate());
    }
}
