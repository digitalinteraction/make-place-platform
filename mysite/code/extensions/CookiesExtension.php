<?php

class CookiesExtension extends Extension {
    
    // If the current user has accepted cookie use
    public function getHasCookieConsent() {
        return CookiesExtension::hasCookieConsent();
    }
    
    public static function hasCookieConsent() {
        return isset($_COOKIE['cookieconsent_status'])
            && $_COOKIE['cookieconsent_status'] !== 'deny';
    }
}
