<?php

/** ... */
class SurveyPageTest extends SapphireTest {
    
    protected $page = null;
    
    public function setUp() {
        parent::setUp();
        
        $this->page = SurveyPage::create([]);
    }
    
    public function testGetCMSFields() {
        
        $fields = $this->page->getCMSFields();
        
        $this->assertNotNull($fields->fieldByName('Root.Survey'));
    }
}
