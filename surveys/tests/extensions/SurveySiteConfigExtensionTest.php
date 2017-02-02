<?php


class MockSurveySiteConfig extends DataObject {


}

class SurveySiteConfigExtensionTest extends SapphireTest {
    
    
    public function setUpOnce() {
        parent::setUpOnce();
        
        MockSurveySiteConfig::add_extension('SurveySiteConfigExtension');
    }
    
    
    public function testExtensionAdded() {
        
        $this->assertTrue(MockSurveySiteConfig::has_extension('SurveySiteConfigExtension'));
    }
    
    public function testSegment() {
        
        $config = MockSurveySiteConfig::create(array(
            "SurveySegment" => "Something unacceptable"
        ));
        $config->onBeforeWrite();
        
        $this->assertEquals("something-unacceptable", $config->SurveySegment);
    }
    
    public function testDefaults() {
        
        $config = MockSurveySiteConfig::create();
        $this->assertEquals('surveys', $config->SurveySegment);
    }
    
    // public function testAddRules() {
    //
    //     $config = MockSurveySiteConfig::create([
    //         "SurveySegment" => "Surveys"
    //     ]);
    //
    //     $config->onBeforeWrite();
    //
    //
    //     $rules = Config::inst()->get('Director', 'rules');
    //
    //     $this->assertTrue(in_array('surveys/$SurveyID', $rules));
    // }
}
