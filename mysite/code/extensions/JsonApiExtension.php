<?php

/** An controller extension to provide json api utilities */
class JsonApiExtension extends Extension {
    
    // Reponses
    /** Returns a json response with json data */
    public function jsonResponse($json = [], $statusCode = 200) {
        
        $response = $this->owner->getResponse();
        
        $response->addHeader("Content-Type", "application/json");
        $response->setBody(json_encode($json));
        $response->setStatusCode($statusCode);
        
        return $response;
    }
    
    /** Returns a json response in airbnb style */
    public function formattedJsonResponse($data, $messages = [], $success = true) {
        
        if (!is_array($messages)) {
            $messages = [$messages];
        }
        
        $status = $success ? 200 : 404;
        
        return $this->jsonResponse([
            'meta' => [
                'success' => $success,
                'messages' => $messages
            ],
            'data' => $data
        ], $status);
    }
    
    /** Returns a json authentication error response */
    public function jsonAuthError($message = "You need to sign in to do that") {
        
        return $this->jsonResponse([$message], 401);
    }
}
