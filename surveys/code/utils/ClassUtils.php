<?php

/** ... */
class ClassUtils extends Object {
    
    public static function getSubclasses($class, $base) {
        
        // Get our subclasses
        $subclasses = ClassInfo::subclassesFor($class);
        
        // For some reason it includes base class too
        // Remove that
        unset($subclasses[$base]);
        
        
        foreach ($subclasses as $key => $value) {
            
            // $nameName = str_replace($base, "", $key);
            // $subclasses[$key] = trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', $nameName));
            
            $subclasses[$key] = self::formatClass($key, $base);
        }
        
        // Return just the values
        return $subclasses;
    }
    
    public static function formatClass($class, $base = null) {
        
        // Give them readable names
        // -> removes '$base' from the end
        // -> adds spaces between words
        //    ref: http://stackoverflow.com/questions/1089613/php-put-a-space-in-front-of-capitals-in-a-string-regex
        
        if ($base != null) {
            $class = str_replace($base, "", $class);
        }
        
        return trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', $class));
    }
}
