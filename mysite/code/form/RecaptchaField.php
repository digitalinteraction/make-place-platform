<?php

/** A FormField that renders a captcha and provides logic to validate it */
class RecaptchaField extends FormField {
    
    public function __construct($name, $title = null, $value = null) {
        
        parent::__construct($name, $title, $value);
        
        // do not need a fallback title if none was defined.
        if (empty($title)) { $this->title = ''; }
    }
    
    public function getPublicKey() {
        return G_RECAPTCHA_PUBLIC;
    }
    
    public function Field($properties = []) {
        return $this->renderWith("RecaptchaField");
    }
    
    
    public function validate($validator) {
        
        // Check the recaptcha was attempted
        if (!isset($_REQUEST['g-recaptcha-response']) && isset($_REQUEST['g-recaptcha-response']) !== "") {
            $validator->validationError($this->name, "Please prove you are human", "warn");
            return false;
        }
        
        
        // Verify the response with google
        $response = $this->recaptchaResponse($_REQUEST['g-recaptcha-response']);
        
        
        // Check if the recaptcha failed
        if ($response == null || !isset($response["success"]) || $response["success"] !== true) {
            $validator->validationError($this->name, "Please prove you are human", "warn");
            return false;
        }
        
        
        return true;
    }
    
    /** @codeCoverageIgnore */
    public function recaptchaResponse($value) {
        
        // Create a curl request to check the recaptcha
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        
        
        // Add the post fields to the curl
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
            "secret" => G_RECAPTCHA_SECRET,
            "response" => $value,
            "remoteip" => $_SERVER['REMOTE_ADDR']
        )));
        
        
        // Execute the curl and get its response string
        $result = curl_exec($ch);
        curl_close($ch);
        
        
        // Decode the response as json
        return json_decode($result, true);
    }
    
}
