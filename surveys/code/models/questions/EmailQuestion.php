<?php
/** A question that asks for an email */
class EmailQuestion extends TextQuestion {
    
    public function getType() {
        return "email";
    }
}
