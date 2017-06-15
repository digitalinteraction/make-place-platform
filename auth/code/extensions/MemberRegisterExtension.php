<?php

/** ... */
class MemberRegisterExtension extends DataExtension {
    
    private static $belongs_to = array(
        "Registration" => "Registration"
    );
    
    /** If this member has verified their email */
    public function getHasVerified() {
        return Permission::check('VERIFIED', 'any', $this->owner);
    }
    
    
    
    /** Give this member voting/commenting rights */
    public function addInteraction() {
        
        // Get the interaction group and add ourself to it
        $group = $this->getVerifiedGroup()
            ->Members()
            ->add($this->owner);
    }
    
    /** Get the interaction group */
    public function getVerifiedGroup() {
        
        // Find the group and return that if it exists
        $group = Group::get()->filter("Code", "verified")->first();
        if ($group) { return $group; }
        
        $group = Group::create([
            "Title" => "Verified",
            "Code" => "verified"
        ]);
        $group->write();
        
        
        // Give it VERIFIED permission
        $permission = Permission::create([
            "Code" => "VERIFIED",
            "Type" => 1,
            "GroupID" => $group->ID
        ]);
        $permission->write();
        
        
        return $group;
    }
}
