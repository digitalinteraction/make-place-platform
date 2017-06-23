<?php

/** An controller extension to provide json api utilities */
class JsonApiExtension extends Extension {
    
    // Reponses
    /** Returns a json response with json data */
    public function jsonResponse($json = [], $statusCode = 200) {
        
        // Get the resposne from our owner
        $response = $this->owner->getResponse();
        
        // Add the json header and set the body & status code
        $response->addHeader("Content-Type", "application/json");
        $response->setBody(json_encode($json));
        $response->setStatusCode($statusCode);
        
        // Return the response
        return $response;
    }
    
    /** Returns a json response in airbnb style */
    public function formattedJsonResponse($data, $messages = [], $success = true) {
        
        // If not passed an array of messages, make it one
        if (!is_array($messages)) { $messages = [$messages]; }
        
        // Set the status code depending on 'success'
        $status = $success ? 200 : 404;
        
        // Format & return a json response
        return $this->jsonResponse([
            'meta' => [
                'success' => $success,
                'messages' => $messages
            ],
            'data' => $data
        ], $status);
    }
    
    /** Returns a json authentication error response */
    public function jsonAuthError($message = "You need to login in to do that") {
        return $this->jsonResponse([$message], 401);
    }
}
