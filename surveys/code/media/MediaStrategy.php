<?php

/** A way a MediaQuestion can store a file, subclasses implement thier own logic */
class MediaStrategy extends Object {
    
    public static function get($type) {
        
        switch ($type) {
        case "S3": return S3MediaStrategy::create();
        default:   return LocalMediaStrategy::create();
        }
    }
    
    
    public function createMedia($values) {
        // Overriden by subclasses
        return null;
    }
    
    public function mediaUrl($media) {
        return $media->Path;
    }
}
