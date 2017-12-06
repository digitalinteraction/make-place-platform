<?php

/** A component to add to a map to render arbitrary html as a popup */
class ContentMapComponent extends MapComponent {
  
    private static $db = [
        'PopupTitle' => 'Varchar(255)',
        'PopupContent' => 'HTMLText',
        'ActionColour' => 'Enum(array("primary", "secondary","blue", "green", "orange", "purple", "red"), "green")'
    ];
    
    private static $defaults = [
        'Title' => 'Info',
        'ActionColour' => 'primary'
    ];
  
    public function addExtraFields(FieldList $fields) {
        
        $actionColours = singleton('ContentMapComponent')->dbObject('ActionColour')->enumValues();
        
        $fields->addFieldsToTab('Root.Main', [
            DropdownField::create('ActionColour', 'Action Colour', $actionColours),
            TextField::create('PopupTitle', 'Popup Title'),
            HtmlEditorField::create('PopupContent', 'Content')
        ]);
    }
}
