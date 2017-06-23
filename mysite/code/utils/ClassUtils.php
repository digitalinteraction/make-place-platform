<?php

/** A utility to get parent classes and format them */
class ClassUtils extends Object {
    
    /** Get the subclasses of a given class, optionally provide a 'base' part to remove from each or if to exclude $class from the result */
    public static function getSubclasses($class, $base = null, $removeParent = false) {
        
        // Get our subclasses
        $subclasses = ClassInfo::subclassesFor($class);
        
        // Remove the base class if specified
        if ($removeParent) {
            unset($subclasses[$base]);
        }
        
        
        // Format each classname
        foreach ($subclasses as $key => $value) {
            $subclasses[$key] = self::formatClass($key, $base);
        }
        
        
        // Return the classes
        return $subclasses;
    }
    
    /** Formats a class name into a readable version, optionally removes a 'base' part from the end */
    public static function formatClass($class, $base = null) {
        
        // Give them readable names
        // -> removes '$base' from the end
        // -> adds spaces between words
        //    ref: http://stackoverflow.com/questions/1089613/php-put-a-space-in-front-of-capitals-in-a-string-regex
        
        // If the base is set & the class isn't the base, remove the base part from the class
        if ($base != null && $class != $base) {
            $class = str_replace($base, "", $class);
        }
        
        // Format the classname
        return trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', $class));
    }
}
