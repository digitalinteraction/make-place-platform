<?php
/**
 *  An Extension to provide a unique random hash salted per for a member
 */
class SecureGeneratorExtension extends Extension {
    
    /**
     *  Generates a unique hash given a member and ensures its correct for a field on a table
     *  @param Member $member The member the hash is for
     *  @param String $table The table to ensure the hash is unique on
     *  @param String $field The field of the table to ensure the hash is unique on
     *  @return String A unique random string
     */
    public function generateUniqueKey($member, $table, $field) {
        
        // Keep generating hashes until it is unique on `table`.`field`
        
        do {
            // Create a generator & generate a random string
			$generator = new RandomGenerator();
			$token = $generator->randomToken();
            
            // Encrypt with the user's settings, making it more unique to the user
			$hash = $member->encryptWithUserSettings($token);
            
		} while(DataObject::get_one($table, array(
            "\"$table\".\"$field\"" => $hash
		)));
        
        // Return the hash
        return $hash;
    }
}
