<?php



/**
 * @apiDefine Member Member access only
 * Authentication requires a valid web session or a valid `apikey`
 */



/** A controller providing json-based api responses */
class ApiController extends Controller {
    
    private static $extensions = [
        'JsonApiExtension', 'ApiAuthExtension'
    ];
    
    
    
    
    
    /* Utils*/
    
    /** Gets a json or post var from the request or adds an error to the array */
    public function bodyVar($name, &$errors = []) {
        
        $post = $this->postVar($name);
        if ($post != null) { return $post; }
        
        $json = $this->jsonVar($name);
        if ($json != null) { return $json; }
        
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
}
