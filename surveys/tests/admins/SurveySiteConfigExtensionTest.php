<?php


class MockSiteConfig extends DataObject {


}

class SurveySiteConfigExtensionTest extends SapphireTest {
    
    
    public function setUpOnce() {
        parent::setUpOnce();
        
        MockSiteConfig::add_extension('SurveySiteConfigExtension');
    }
    
    
    public function testExtensionAdded() {
        
        $this->assertTrue(MockSiteConfig::has_extension('SurveySiteConfigExtension'));
    }
    
    public function testSegment() {
        
        $config = MockSiteConfig::create(array(
            "SurveySegment" => "Something unacceptable"
        ));
        $config->onBeforeWrite();
        
        $this->assertEquals("something-unacceptable", $config->SurveySegment);
    }
    
    public function testDefaults() {
        
        $config = MockSiteConfig::create();
        $this->assertEquals('surveys', $config->SurveySegment);
    }
    
    // public function testAddRules() {
    //
    //     // echo "Inbound";
    //
    //     $config = MockSiteConfig::create([
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
