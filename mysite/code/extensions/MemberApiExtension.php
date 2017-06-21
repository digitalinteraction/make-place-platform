<?php

/** An extension for member to whitelist api fields */
class MemberApiExtension extends DataExtension {
    
    public function includedFields() {
        return [ 'FirstName', 'Surname', 'ProfileImageID' ];
    }
}
