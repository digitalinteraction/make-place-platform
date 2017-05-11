<?php

/** Tests LocalMediaStrategy */
class LocalMediaStrategyTest extends SapphireTest {
    
    protected $strategy = null;
    public $usesDatabase = true;
    
    public function setUp() {
        parent::setUp();
        
        $this->strategy = LocalMediaStrategy::create();
    }
    
    
    public function createSomeMedia() {
        
        $values = [
            "name" => "image.png",
            "type" => "image/png",
            "tmp_name" => "/tmp/test_image.png",
            "error" => 0,
            "size" => 13181
        ];
        
        // Put file in a temporary location
        $file = fopen($values["tmp_name"], "w");
        fwrite($file, "Some dummy data\n");
        fclose($file);
        
        return $this->strategy->createMedia($values);
    }
    
    
    public function testCreateMedia() {
        
        $mediaId = $this->createSomeMedia();
        $this->assertNotNull($mediaId);
    }
    
    public function testCreateMediaProperties() {
        
        $mediaId = $this->createSomeMedia();
        
        // Check the properties were set on the media record
        $media = SurveyMedia::get()->byID($mediaId);
        $this->assertEquals("image", $media->Name);
        $this->assertNotNull($media->Filename);
        $this->assertNotNull($media->Path);
        $this->assertEquals("image/png", $media->Type);
        $this->assertEquals(13181, $media->Size);
    }
    
    public function testFilename() {
        
        $name = $this->strategy->filename("test.png");
        $this->assertNotNull($name);
    }
    
    public function testExtension() {
        
        $ext = $this->strategy->extension("test.png");
        $this->assertEquals("png", $ext);
    }
    
    public function testName() {
        $name = $this->strategy->name("/tmp/test.png");
        $this->assertEquals("test", $name);
    }
}
