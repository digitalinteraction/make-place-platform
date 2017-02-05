<?php

/** ... */
class JsonApiExtension extends Extension {
    
    public function jsonResponse($json = [], $statusCode = 200) {
        
        $response = $this->owner->getResponse();
        
        $response->addHeader("Content-Type", "application/json");
        $response->setBody(json_encode($json));
        $response->setStatusCode($statusCode);
        
        return $response;
    }
    
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
}
