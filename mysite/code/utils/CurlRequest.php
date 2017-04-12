<?php


class CurlRequest extends Object {
    
    protected $url = null;
    protected $getVars = [];
    protected $responseBody = null;
    protected $responseCode = null;
    
    public static $testResponse = null;
    
    public function __construct($url, $getVars = []) {
        
        $this->url = $url;
        $this->getVars = $getVars;
    }
    
    public function execute() {
        
        // Don't execute the request again
        if ($this->responseBody) { return $this; }
        
        
        // If testing, return the test response
        if (self::$testResponse) {
            $this->responseBody = self::$testResponse;
            $this->responseCode = 200;
            self::$testResponse = null;
            return $this;
        }
        
        
        // Create a curl request
        $ch = curl_init($this->constructUrl());
        
        
        // Setup the request
        curl_setopt_array($ch, [
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 1,
            CURLINFO_HEADER_OUT => true,
        ]);
        
        
        // Execute the request
        $this->responseBody = curl_exec($ch);
        $this->responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        
        // Close the curl connection
        curl_close($ch);
        
        
        // Return ourself for chaining
        return $this;
    }
    
    
    
    public function getGetVars() { return $this->getVars; }
    
    public function getUrl() { return $this->url; }
    
    
    public function constructUrl() {
        return $this->url."?".http_build_query($this->getVars);
    }
    
    
    public function responseBody() {
        $this->execute();
        return $this->responseBody;
    }
    
    public function responseCode() {
        $this->execute();
        return $this->responseCode;
    }
    
    public function jsonResponse() {
        $this->execute();
        return json_decode($this->responseBody, true);
    }
    
    
    public static function validApiResponse($response) {
        
        return isset($response['meta'])
            && isset($response['meta']['success'])
            && isset($response['data'])
            && $response['meta']['success'];
    }
}
