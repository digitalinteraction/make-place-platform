<?php
/*
 *  A Test case to run tests on Pages
 */
class PageTest extends SapphireTest {
    
    protected static $fixture_file = "mysite/tests/fixtures/pages.yml";
    
    
    public function setUp() {
        
        parent::setUp();
        
        // ...
    }
    
    
    public function testInit() {
        
        $page = $this->objFromFixture('Page', 'SomePage');
        
        $this->assertNotNull($page);
    }
}
