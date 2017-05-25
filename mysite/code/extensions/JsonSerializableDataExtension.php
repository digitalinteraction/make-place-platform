<?php

/** An extension for DataObject to make them json serializable */
class JsonSerializableDataExtension extends DataExtension {
    
    
    public function excludedFields() {
        
        $curClass = $this->owner->class;
        $fields = [];
        
        // Iterate up the inheritance
        while($curClass != 'DataObject') {
            
            // Reflect the class so we can get private properties
            $reflectionClass = new ReflectionClass($curClass);
            
            // Check this class has the property
            if ($reflectionClass->hasProperty('excluded_fields')) {
                
                // Get the property & ignore private/protected
                $property = $reflectionClass->getProperty('excluded_fields');
                $property->setAccessible(true);
                
                // Merge the value into our array
                $fields = array_merge($fields, $property->getValue());
            }
            
            // Increment the loop by going to the parent class
            $curClass = get_parent_class($curClass);
        }
        
        // Return the fields
        return $fields;
    }
    
    
    public function jsonSerialize() {
        
        $keys = array_diff(
            array_keys($this->owner->inheritedDatabaseFields()),
            $this->owner->excludedFields()
        );
        
        // Start with the base properties
        $json = [
            'id' => $this->owner->ID,
            'className' => $this->owner->ClassName,
            'created' => $this->owner->Created,
            'lastEdited' => $this->owner->LastEdited
        ];
        
        // Add each value using our keys
        foreach($keys as $field) {
            $json[lcfirst($field)] = $this->owner->$field;
        }
        
        // Return the json array
        return $json;
    }
}
