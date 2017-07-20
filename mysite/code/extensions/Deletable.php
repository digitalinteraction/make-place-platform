<?php

class Deletable extends DataExtension {
    
    private static $db = [
        'Deleted' => 'Boolean'
    ];
    
    public function updateCMSFields(FieldList $fields) {
        
        $fields->addFieldsToTab('Root.Main', [
            CheckboxField::create('Deleted', 'Deleted')
                ->setDescription("Mark as deleted without actually removing the record")
        ]);
    }
}
