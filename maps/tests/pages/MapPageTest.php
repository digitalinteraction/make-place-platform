<?php

/** Tests MapPage */
class MapPageTest extends FunctionalTest {
    
    // Load db objects from a file
    protected static $fixture_file = "maps/tests/fixtures/mapPage.yml";
    
    protected $page = null;
    protected $controller = null;
    
    public function setUp() {
        parent::setUp();
        
        $this->page = $this->objFromFixture('MapPage', 'map');
        
        $this->controller = MapPage_Controller::create($this->page);
    }
    
    public function testInit() {
        
        $this->assertNotNull($this->page);
        $this->assertNotNull($this->controller);
        $this->assertEquals(1, $this->page->MapComponents()->count());
    }
    
    public function testCMSMapsTab() {
        
        // Create a test page
        $page = MapPage::create([]);
        
        // Get its fields
        $fields = $page->getCMSFields();
        
        // Test the maps tab was added
        $this->assertNotNull($fields->fieldByName('Root.Maps'));
    }
    
    public function testCMSComponentsTab() {
        
        // Create a test page
        $page = MapPage::create([]);
        
        // Get its fields
        $fields = $page->getCMSFields();
        
        // Test the maps tab was added
        $this->assertNotNull($fields->fieldByName('Root.Components'));
    }
    
    
    
    public function testMapConfig() {
        
        $response = $this->controller->mapConfig();
        
        $config = json_decode($response->getBody(), true);
        
        $expected = [
            'components' => [
                [
                    'type' => 'SurveyMapComponent'
                ]
            ]
        ];
        
        $this->assertArraySubset($expected, $config);
    }
    
    public function testGetActions() {
        
        $this->assertEquals(1, $this->controller->getActions()->count());
    }
}
