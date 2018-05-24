<?php

/** An extension for members to add consent fields */
class ConsentMemberExtension extends DataExtension {
  
    private static $db = [
      'ConsentUpdated' => 'Date',
      'ConsentStatus' => 'Enum(array("Signup","Accept","Reject",),"Signup")'
    ];
}
