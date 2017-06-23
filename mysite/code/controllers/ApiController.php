<?php


// Custom apidocjs definitions, allows re-use across different files
/**
 * @apiDefine Member Member access only
 * Authentication requires a valid web session or a valid `apikey`
 */



/** A controller providing json-based api responses */
class ApiController extends Controller {
    
    private static $extensions = [ 'JsonApiExtension', 'ApiAuthExtension' ];
    
    
    /* Utils */
    
    /** Gets a json or post var from the request or adds an error to the array */
    public function bodyVar($name, &$errors = []) {
        
        $post = $this->postVar($name);
        if ($post !== null) { return $post; }
        
        $json = $this->jsonVar($name);
        if ($json !== null) { return $json; }
        
        $errors[] = "Please provide '$name'";
        return null;
    }
    
    /** Gets a post var from the request or adds an error to the array */
    public function postVar($name, &$errors = []) {
        
        if ($this->request->postVar($name) != null) {
            return $this->request->postVar($name);
        }
        
        $errors[] = "Please provide '$name'";
        return null;
    }
    
    /** Gets a json var from the request or adds an error to the array */
    public function jsonVar($name, &$errors = []) {
        
        if ($this->jsonBody == null) {
            $this->jsonBody = json_decode($this->request->getBody(), true);
        }
        
        if (isset($this->jsonBody[$name])) {
            return $this->jsonBody[$name];
        }
        
        $errors[] = "Please provide '$name'";
        
        return null;
    }
    
    /** Gets a query var from the request or adds an error to the array */
    public function getVar($name, &$errors = []) {
        
        if ($this->request->getVar($name) != null) {
            return $this->request->getVar($name);
        }
        
        $errors[] = "Please provide '$name'";
        return null;
    }
    
    /** Gets an object of a given type by its id, or creates errors */
    function findObject($type, $id, &$errors = []) {
        
        $errorMsg = "$type($id)";
        
        // Check the class exists
        if (!class_exists($type)) {
            $errors[] = "Unknown Target $errorMsg"; return null;
        }
        
        // Reflect the class to check its a DataObject
        $reflection = new ReflectionClass($type);
        if (!$reflection->isSubclassOf('DataObject')) {
            $errors[] = "Invalid Target $errorMsg"; return null;
        }
        
        // Fetch the object
        $object = $type::get()->byID($id);
        
        // Check the object exists
        if (!$object) {
            $errors[] = "$errorMsg does not exist"; return null;
        }
        
        // Return the object
        return $object;
    }
}
