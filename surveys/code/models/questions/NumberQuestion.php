<?php
/** A question that asks for a number */
class NumberQuestion extends Question {
    
    public function getType() {
        return "number";
    }
}
