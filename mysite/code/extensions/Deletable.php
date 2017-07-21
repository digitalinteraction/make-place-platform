<?php

class Deletable extends DataExtension {
    
    private static $db = [
        'Deleted' => 'Boolean'
    ];
    
    public function deletedField() {
        return CheckboxField::create('Deleted', 'Deleted')
            ->setDescription("Mark as deleted without actually removing the record");
    }
    
    public function updateCMSFields(FieldList $fields) {
        
        $fields->addFieldsToTab('Root.Main', [ $this->deletedField() ]);
    }
}
