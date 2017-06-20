<?php

/** Tests ContentMapComponent */
/** @group whitelist */
class ContentMapComponentTest extends SapphireTest {
    
    public function testExtraFields() {
        
        $component = ContentMapComponent::create();
        $component->ID = 7;
        
        $fields = $component->getCMSFields();
        
        $this->assertNotNull($fields->fieldByName('Root.Main.PopupTitle'));
        $this->assertNotNull($fields->fieldByName('Root.Main.PopupContent'));
    }
  
    public function testConfigData() {
        
        $component = ContentMapComponent::create([
            'PopupTitle' => 'Title',
            'PopupContent' => 'Content'
        ]);
        
        $expected = [
            'popupTitle' => 'Title',
            'popupContent' => 'Content'
        ];
        
        $data = $component->configData();
        
        $this->assertArraySubset($expected, $data);
    }
}
