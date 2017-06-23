<?php

/* Test Page */
class PageTest extends SapphireTest {
    
    public function setUp() {
        
        parent::setUp();
        
        // ...
        
        $this->page = Page::create([
            "URLSegment" => "test/",
            "Title" => "Test Page",
            "MenuTitle" => "Test Page",
            "Content" => "<p> Hello, World! </p>"
        ]);
    }
    
    
    public function testInit() {
        
        $this->assertNotNull($this->page);
    }
    
    
    public function testGetSiteTree() {
        
        // Make sure it renders something
        $render = $this->page->getSitetree();
        $this->assertNotNull($render);
    }
    
    
    
    
    public function testControllerAdmin() {
        
        $controller = Page_Controller::create($this->page);
        
        $response = $controller->admin();
        
        $this->assertEquals(302, $response->getStatusCode());
    }
    
    public function testGetShouldFillScreen() {
        
        $this->assertEquals("fill", $this->page->getShouldFillScreen());
    }
}
