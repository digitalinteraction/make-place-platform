<?php
/**
 *  An extension to check recaptcha responses
 */
class RecaptchaExtension extends Extension {
    
    public function addRecaptchaJs() {
        
        Requirements::javascript("https://www.google.com/recaptcha/api.js");
    }
    
    public function recaptchaResponse($data) {
        
        $response = $data["g-recaptcha-response"];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        
        // Add the post fields to the curl
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
            "secret" => G_RECAPTCHA_SECRET,
            "response" => $response,
            "remoteip" => $_SERVER['REMOTE_ADDR']
        )));
        
        // Execute the curl and get its response string
        $result = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($result, true);
    }
    
    public function processRecaptcha($data, $form) {
        
        // Check captcha was used
        if (!isset($data["g-recaptcha-response"])) {
            $form->sessionMessage("Please prove you are human", 'warn');
            return $this->owner->redirectBack();
        }
        
        
        // Check the captcha response
        $captcha = $this->recaptchaResponse($data);
        
        
        // Check Captcha passed
        if ($captcha == null || !isset($captcha["success"]) || $captcha["success"] !== true) {
            $form->sessionMessage("Please prove you are human", 'warn');
            return $this->owner->redirectBack();
        }
        
        
        return null;
    }
}
