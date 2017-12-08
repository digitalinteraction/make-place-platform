<?php

/** Tests ContentMapComponent */
class ContentMapComponentTest extends SapphireTest {
    
    public function testExtraFields() {
        
        // Create a component and pretend it was written to the db
        $component = ContentMapComponent::create();
        $component->ID = 7;
        
        
        // Make it generate its fields
        $fields = $component->getCMSFields();
        
        
        // Check it added the right fields
        $this->assertNotNull($fields->fieldByName('Root.Main.PopupTitle'));
        $this->assertNotNull($fields->fieldByName('Root.Main.PopupContent'));
        $this->assertNotNull($fields->fieldByName('Root.Main.ActionColour'));
    }
  
    public function testJson() {
        
        // Create a component with configuration
        $component = ContentMapComponent::create([
            'PopupTitle' => 'Title',
            'PopupContent' => 'Content'
        ]);
        
        // The json we want it to produce
        $expected = [
            'popupTitle' => 'Title',
            'popupContent' => 'Content',
            'actionColour' => 'primary'
        ];
        
        // Check its config data is correct
        $this->assertArraySubset($expected, $component->jsonSerialize());
    }
}
