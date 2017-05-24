<?php

/** Tests CommentApiController */
/** @group whitelist */
class CommentApiControllerTest extends FunctionalTest {
    
    public $apiBase = "api/comment";
    
    public function setUp() {
        parent::setUp();
    }
    
    
    public function testChildrenRoute() {
        
        $res = $this->get("{$this->apiBase}/1/children");
        
        $this->assertNotEquals(404, $res->getStatusCode());
    }
    
    public function testIndexRoute() {
        
        $res = $this->get("{$this->apiBase}/on/Response/1");
        
        $this->assertNotEquals(404, $res->getStatusCode());
    }
    
    public function testCreateRoute() {
        
        $res = $this->post("{$this->apiBase}/on/Response/1", []);
        
        $this->assertNotEquals(404, $res->getStatusCode());
    }
}
