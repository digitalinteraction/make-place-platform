<?php

/** Tests MediaQuestion */
/** @group whitelist */
class MediaQuestionTest extends SapphireTest {
    
    public $usesDatabase = true;
    protected $question = null;
    
    public function setUp() {
        parent::setUp();
        
        $this->question = MediaQuestion::create([
            "Name" => "Media Question",
            "Handle" => "media-question",
            "MediaType" => "any",
            "Strategy" => "LOCAL",
            "Required" => true
        ]);
    }
    
    public function testExtraFields() {
        
        $extras = $this->question->extraFields();
        $this->assertEquals(2, count($extras));
    }
    
    
    /* Test rendering */
    public function testRenderImage() {
        
        // Create an image media
        $media = SurveyMedia::create([
            "Path" => "some/file.png",
            "Type" => "image/png"
        ]);
        
        $media->write();
        
        $rendered = $this->question->renderResponse($media->ID);
        
        // Check it created an image tag
        $this->assertRegexp('/<img/', $rendered);
        
        // Check it put the path of the image in
        $this->assertRegexp('/some\/file.png/', $rendered);
    }
    
    public function testRenderVideo() {
        
        // Create an image media
        $media = SurveyMedia::create([
            "Path" => "some/file.mp4",
            "Type" => "video/mp4"
        ]);
        
        $media->write();
        
        $rendered = $this->question->renderResponse($media->ID);
        
        // Check it created a video tag
        $this->assertRegexp('/<video/', $rendered);
        
        // Check it put the path of the video in
        $this->assertRegexp('/some\/file.mp4/', $rendered);
    }
    
    public function testRenderAudio() {
        
        // Create an image media
        $media = SurveyMedia::create([
            "Path" => "some/file.mp3",
            "Type" => "video/mp3"
        ]);
        
        $media->write();
        
        $rendered = $this->question->renderResponse($media->ID);
        
        // Check it created an audio tag
        $this->assertRegexp('/<video/', $rendered);
        
        // Check it put the path of the audio in
        $this->assertRegexp('/some\/file.mp3/', $rendered);
    }
    
    
    /* Value Validation */
    public function testValidateValueWithMediaId() {
        
        $media = SurveyMedia::create([]);
        $media->write();
        
        $errors = $this->question->validateValue($media->ID);
        
        $this->assertEquals(0, count($errors));
    }
    
    public function testValidateValueWithInvalidMediaId() {
        
        $errors = $this->question->validateValue(1);
        
        $this->assertEquals(1, count($errors));
    }
    
    public function testValidateValueWithFile() {
        
        $value = [
            "name" => "test.png",
            "type" => "image/png",
            "tmp_name" => "/tmp/my_image",
            "size" => 12345
        ];
        
        $errors = $this->question->validateValue($value);
        
        $this->assertEquals(0, count($errors));
    }
    
    public function testValidateValueWithInvalidFile() {
        
        $errors = $this->question->validateValue("Something");
        
        $this->assertEquals(4, count($errors));
    }
    
    
    /* Value Packing */
    public function testPackValueWithMediaId() {
        
        $packed = $this->question->packValue("1");
        
        $this->assertEquals("1", $packed);
    }
    
    
    /* Value Unpacking */
    public function testUnpackValue() {
        
        $media = SurveyMedia::create();
        $media->write();
        
        $unpacked = $this->question->unpackValue($media->ID);
        
        $this->assertNotNull($unpacked);
    }
    
    
    /* Misc */
    public function testResponseCreated() {
        
        // Create a SurveyMedia to relate to
        $media = SurveyMedia::create();
        $media->write();
        
        // Create our response
        $response = SurveyResponse::create([]);
        $response->write();
        
        // Call the callback
        $this->question->responseCreated($response, $media->ID);
        
        // Check the relaition was setup
        $this->assertEquals(1, $response->Media()->count());
        $this->assertEquals(1, $media->Responses()->count());
    }
}
