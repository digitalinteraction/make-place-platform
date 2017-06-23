<?php

/** A hidden question */
class HiddenQuestion extends Question {
    
    // NOTE: Kinda pointless ?! ...
    
    public function getType() {
        return "hidden";
    }
}
