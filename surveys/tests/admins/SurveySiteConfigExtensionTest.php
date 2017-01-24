<?php

class SurveySiteConfigExtensionTest extends SapphireTest {
    
    
    
    public function testSegment() {
        
        $config = SiteConfig::create(array(
            "SurveySegment" => "Something unacceptable"
        ));
        $config->write();
        
        // $this->assertEquals("something-unacceptable", $config->SurveySegment);
    }
    
    public function testDefaults() {
        
        // $config = SiteConfig::create();
        // $this->assertEquals('surveys', $config->SurveySegment);
    }
}
