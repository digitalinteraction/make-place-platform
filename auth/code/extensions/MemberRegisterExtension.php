<?php

/** ... */
class MemberRegisterExtension extends DataExtension {
    
    private static $belongs_to = array(
        "Registration" => "Registration"
    );
    
    /** If this member can vote / comment */
    public function getCanInteract() {
        return Permission::check('CAN_INTERACT', 'any', $this->owner);
    }
    
    
    
    /** Give this member voting/commenting rights */
    public function addInteraction() {
        
        // Get the interaction group and add ourself to it
        $group = $this->getInteractiveGroup()
            ->Members()
            ->add($this->owner);
    }
    
    /** Get the interaction group */
    public function getInteractiveGroup() {
        
        $group = Group::get()->filter("Code", "interactive")->first();
        
        if ($group) { return $group; }
        
        $group = Group::create([
            "Title" => "Interactive",
            "Code" => "interactive"
        ]);
        $group->write();
        
        
        // Give it CAN_INTERACT permission
        $permission = Permission::create([
            "Code" => "CAN_INTERACT",
            "Type" => 1,
            "GroupID" => $group->ID
        ]);
        $permission->write();
        
        
        return $group;
    }
}
