<?php

/**
 *
 */
class SampleTest extends SapphireTest {
    
    protected static $fixture_file = "mysite/tests/fixtures/pages.yml";
    
    
    
    
    public function setUp() {
        parent::setUp();
        
        // ...
    }
    
    
    public function testPass() {
        
        // $page = $this->testPage;
        $page = $this->objFromFixture('Page', 'SomePage');
        
        $this->assertNotNull($page);
    }
    
    // public function testFailure() {
    //
    //     return $this->fail();
    // }
}
