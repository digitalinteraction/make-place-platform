<?php
/** Represents when someone has registered to become a member of the site */
class Registration extends DataObject implements PermissionProvider {
    
    private static $extensions = [ 'SecureGeneratorExtension' ];
    
    private static $db = array(
        "Key" => "Varchar(255)",
        "Active" => "Boolean",
    );
    
    private static $has_one = array(
        "Member" => "Member"
    );
    
    
    public function providePermissions() {
        return [ "VERIFIED" => "Has verified their email address" ];
    }
}
