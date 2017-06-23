<?php
/** An extension to member to add custom fields and relations along with some permission checking methods */
class MemberOAuthExtension extends DataExtension {
    
    private static $db = array(
        "OAuthToken" => "Varchar(255)",
        "OAuthID" => "Varchar(255)",
        "AuthType" => 'Enum(array("Default","Facebook","Twitter", "Google"))'
    );
    
}
