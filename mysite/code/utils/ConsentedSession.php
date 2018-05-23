<?php

class ConsentedSession extends Object {
    
    public static function get($name) {
        if (!CookiesExtension::hasCookieConsent()) return null;
        return Session::get($name);
    }
    
    public static function set($name, $val) {
        if (!CookiesExtension::hasCookieConsent()) return;
        return Session::set($name, $val);
    }
}
