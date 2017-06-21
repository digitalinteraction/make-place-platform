<?php

/** An extension for DataObject to make them json serializable */
class JsonSerializableDataExtension extends DataExtension {
    
    
    public function excludedFields() {
        
        return $this->traverseArrayProperty("excluded_fields", $this->owner->class);
    }
    
    public function includedFields() {
        
        return $this->traverseArrayProperty("included_fields", $this->owner->class);
    }
    
    public function traverseArrayProperty($name, $class) {
        
        // Start with the class of our owner (the thing we're extending)
        $curClass = $class;
        $values = [];
        
        
        // Iterate upto DataObject
        while($curClass != 'DataObject') {
            
            // Reflect the class so we can get private properties
            $reflectionClass = new ReflectionClass($curClass);
            
            // Check this class has the property
            if ($reflectionClass->hasProperty($name)) {
                
                // Get the property & ignore private/protected
                $property = $reflectionClass->getProperty($name);
                $property->setAccessible(true);
                
                // Merge the value into our array
                $values = array_merge($property->getValue(), $values);
            }
            
            // Increment the loop by going to the parent class
            $curClass = get_parent_class($curClass);
        }
        
        // Return merged value
        return $values;
    }
    
    
    public function jsonSerialize() {
        
        $allFields = array_keys($this->owner->inheritedDatabaseFields());
        
        // By default, use 'included_fields' on a DataObject
        $keys = array_intersect($allFields, $this->owner->includedFields());
        $excluded = $this->owner->excludedFields();
        
        // If included_fields is not set, and blacklisted are, use them
        if (count($keys) == 0 && count($excluded) > 0) {
            
            // Blacklist from the database fields on the object
            $keys = array_diff(
                $allFields,
                $this->excludedFields($allFields)
            );
        }
        
        // Start with the base properties
        $json = [
            'id' => $this->owner->ID,
            'className' => $this->owner->ClassName,
            'created' => $this->owner->Created
        ];
        
        // Add each value using our keys
        foreach($keys as $field) {
            $json[lcfirst($field)] = $this->owner->$field;
        }
        
        if (method_exists($this->owner, 'customiseJson')) {
            $json = $this->owner->customiseJson($json);
        }
        
        // Return the json array
        return $json;
    }
}
