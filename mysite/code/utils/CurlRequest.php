<?php

/**
 * @codeCoverageIgnore
 * Provides an access to curl to allow mocking in unit tests
 */
class CurlRequest extends Object {
    
    protected $method = "GET";
    protected $url = null;
    protected $getVars = [];
    protected $body = null;
    protected $headers = [];
    protected $responseBody = null;
    protected $responseCode = -1;
    
    public static $testResponse = null;
    
    
    /* Request Lifecycle */
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
        curl_setopt_array($ch, $this->curlOptions());
        
        
        // Execute the request
        $this->responseBody = curl_exec($ch);
        $this->responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        
        // Close the curl connection
        curl_close($ch);
        
        
        // Return ourself for chaining
        return $this;
    }
    
    public function curlOptions() {
        
        $options = [
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 1,
            CURLINFO_HEADER_OUT => true,
        ];
        
        if ($this->body) {
            $options[CURLOPT_POSTFIELDS] = $this->body;
        }
        
        if (count($this->headers) > 0) {
            $options[CURLOPT_HTTPHEADER] = $this->headers;
        }
        
        switch($this->method) {
            case "POST": $options[CURLOPT_POST] = 1; break;
            // case "PUT": $options[CURLOPT_PUT] = 1; break;
            // More methods ...
        }
        
        return $options;
    }
    
    public function constructUrl() {
        return $this->url."?".http_build_query($this->getVars);
    }
    
    
    
    
    /* Request Properties */
    public function getUrl() {
        return $this->url;
    }
    
    public function getGetVars() {
        return $this->getVars;
    }
    
    
    
    
    /* Request Configuration */
    public function setMethod($method) {
        $this->method = $method;
        return $this;
    }
    
    public function setBody($body) {
        $this->body = $body;
        return $this;
    }
    
    public function setJsonBody($json) {
        $this->body = json_encode($json);
        $this->addHeader("Content-Type", "application/json");
        $this->addHeader("Content-Length", strlen($this->body));
        return $this;
    }
    
    public function addHeader($key, $value) {
        $this->headers[] = "$key: $value";
        return $this;
    }
    
    
    
    
    
    
    
    /* Responses */
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
    
    
    
    /* Utils */
    public static function validApiResponse($response) {
        
        return isset($response['meta'])
            && isset($response['meta']['success'])
            && isset($response['data'])
            && $response['meta']['success'];
    }
}



// class CurlResponse extends Object {
//
//     protected $code;
//     protected $body;
//
//     public function __construct($code, $body) {
//
//     }
// }
