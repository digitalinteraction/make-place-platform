<?php

/** A component to add to a map to render arbitrary html as a popup */
class ContentMapComponent extends MapComponent {
  
    private static $db = [
        'PopupTitle' => 'Varchar(255)',
        'PopupContent' => 'HTMLText'
    ];
    
    private static $defaults = [
        'Title' => 'Info'
    ];
  
    public function addExtraFields(FieldList $fields) {
        
        $fields->addFieldsToTab('Root.Main', [
            TextField::create('PopupTitle', 'Popup Title'),
            HtmlEditorField::create('PopupContent', 'Content')
        ]);
    }
  
    public function configData() {
        
        $data = parent::configData();
        
        $data += [
            'popupTitle' => $this->PopupTitle,
            'popupContent' => $this->PopupContent
        ];
        
        return $data;
    }
}
