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
    
    public function testCmsFields() {
        
        $s1 = Survey::create(["Name" => "My Survey"]);
        
        $fields = $s1->getCMSFields();
        
        $this->assertTrue($fields->count() > 0);
    }
    
    
    public function testSecurityToken() {
        
        $survey = Survey::create([
            "Name" => "My Fancy Survey"
        ]);
        
        $this->assertNotNull($survey->getSecurityToken());
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
    
    public function testToggleSecurity() {
        
        $survey = Survey::create([
            "Name" => "My Fancy Survey"
        ]);
        
        $survey->toggleSecurity(false);
        
        $this->assertTrue(is_a($survey->getSecurityToken(), 'NullSecurityToken'));
    }
    
    public function testSurveyUrl() {
        
        $survey = Survey::create([
            "Name" => "My Fancy Survey"
        ]);
        $survey->write();
        
        $this->assertEquals("/s/{$survey->ID}/submit", $survey->getSurveyUrl());
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
    
    
    
    /*
     *  Test rendering
     */
    public function testRender() {
        
        $survey = Survey::create([
            "Name" => "My Fancy Survey"
        ]);
        
        $this->assertNotNull($survey->forTemplate());
    }
}
