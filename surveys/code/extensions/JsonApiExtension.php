<?php

/** ... */
class JsonApiExtension extends Extension {
    
    public function jsonResponse($json = []) {
        
        $response = $this->owner->getResponse();
        
        $response->addHeader("Content-Type", "application/json");
        $response->setBody(json_encode($json));
        
        return $response;
    }
    
    public function formattedJsonResponse($data, $messages = [], $success = true) {
        
        if (!is_array($messages)) {
            $messages = [$messages];
        }
        
        return $this->jsonResponse([
            'meta' => [
                'success' => $success,
                'messages' => $messages
            ],
            'data' => $data
        ]);
    }
}
