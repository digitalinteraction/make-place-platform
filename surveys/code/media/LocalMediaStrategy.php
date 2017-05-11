<?php

// Useful: https://davidwalsh.name/basic-php-file-handling-create-open-read-write-append-close-delete

/** A MediaStrategy to store files locally */
class LocalMediaStrategy extends MediaStrategy {
    
    public $relDirectory = "/assets/surveymedia";
    public $baseDirectory = "/app/assets/surveymedia";
    
    public function extension($filename) {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }
    
    public function name($filename) {
        return pathinfo($filename, PATHINFO_FILENAME);
    }
    
    public function filename($file) {
        return hash('sha256', $file).".".$this->extension($file);
    }
    
    public function createMedia($values) {
        
        // Generate a unique filename
        $filename = $this->filename($values["name"]);
        
        // Work out where to put the file
        $dest = "{$this->baseDirectory}/{$filename}";
        $relative = "{$this->relDirectory}/{$filename}";
        
        // Move the file there
        rename($values["tmp_name"], $dest);
        
        // Store the file
        $media = SurveyMedia::create([
            "Name" => $this->name($values["name"]),
            "Filename" => $filename,
            "Path" => $relative,
            "Type" => $values["type"],
            "Size" => $values["size"]
        ]);
        $media->write();
        
        // Return the id of the file
        return $media->ID;
    }
}
