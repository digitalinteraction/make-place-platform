<?php
/** Represents a key which a user can use to access the api */
class ApiKey extends DataObject {
    
    private static $extensions = [ 'SecureGeneratorExtension' ];
    
    /* The ApiKey's database fields */
    private static $db = [
        "Name" => "Varchar(50)",
        "Key" => "Varchar(255)",
        "Active" => "Boolean",
        "Domains" => "Varchar(255)"
    ];
    
    /* The ApiKey's to-one relations */
    private static $has_one = [
        'Member' => 'Member'
    ];
    
    /* The fields to summarise in ApiAdmin */
    private static $summary_fields = [
        'Member.Title', 'Name', 'Key'
    ];
    
    private static $defaults = [
        'Active' => true
    ];
    
    
    
    /* The fields to edit an ApiKey in ApiAdmin, tweaking the default values */
    public function getCMSFields() {
        
        // Get the default fields generated by silverstripe
        $fields = parent::getCMSFields();
        
        // Remove the key field
        $fields->removeFieldFromTab('Root.Main', 'Key');
        
        
        // If this is being created, set default values
        if ($this->ID == null) {
            $fields->fieldByName("Root.Main.MemberID")->setValue(Member::currentUserID());
        }
        else {
            
            // If showing an existing apikey
            
            // Add the key as a readonly field
            $fields->addFieldToTab('Root.Main',
                ReadonlyField::create('Key','Key'),
                'Active'
            );
            
            // Remove the user picker and just show the owner's name
            $fields->removeFieldFromTab("Root.Main", "MemberID");
            $fields->addFieldToTab("Root.Main", ReadonlyField::create("User")
                ->setValue($this->Member()->getTitle()
            ), 'Active');
            
        }
        
        // Return the fields
        return $fields;
    }
    
    /** Event handler called before writing to the database. */
    public function onBeforeWrite() {
        
        parent::onBeforeWrite();
        
        // If the key isn't set and a member is
        if ($this->Key == null && $this->MemberID != null) {
            
            // Generate a key
            $this->Key = $this->generateUniqueKey($this->Member(), "ApiKey", "Key");
        }
    }
}
