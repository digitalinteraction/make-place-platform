<?php

/** Tests MediaStrategy */
class MediaStrategyTest extends SapphireTest {
    
    public function testS3Strategy() {
        $this->assertNotNull(MediaStrategy::get("S3"));
    }
    
    public function testLocalStrategy() {
        $this->assertNotNull(MediaStrategy::get("LOCAL"));
    }
    
    public function testDefaultCreateMedia() {
        $this->assertNull(MediaStrategy::create()->createMedia([]));
    }
}
