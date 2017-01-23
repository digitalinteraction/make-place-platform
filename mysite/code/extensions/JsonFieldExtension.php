<?php

/** An extension for DataObjects to quickly get a json value from a JsonText field */
class JsonFieldExtension extends Extension {
    
    public function jsonField($name) {
        
        // Get the field from the owner
        $field = $this->owner->dbObject($name);
        
        // Return the json or null if there isn't a field
        return ($field) ? $field->valueAsJson() : null;
    }
}
