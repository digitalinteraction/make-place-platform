<?php

/** Tests MediaStrategy */
class MediaStrategyTest extends SapphireTest {
    
    public $usesDatabase = true;
    
    public function testS3Strategy() {
        $this->assertNotNull(MediaStrategy::get("S3"));
    }
    
    public function testLocalStrategy() {
        $this->assertNotNull(MediaStrategy::get("LOCAL"));
    }
    
    public function testDefaultCreateMedia() {
        $this->assertNull(MediaStrategy::create()->createMedia([]));
    }
    
    public function testMediaUrl() {
        $media = SurveyMedia::create(["Path" => "some/path.png"]);
        $media->write();
        
        $path = MediaStrategy::create()->mediaUrl($media);
        $this->assertEquals("some/path.png", $path);
    }
}
