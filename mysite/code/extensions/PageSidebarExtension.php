<?php

/*
 *  An extension for pages to add a util for providing columns for sidebars, toggleable in the cms
 */
class PageSidebarExtension extends DataExtension {
    
    private static $db = array(
        'ShowSidebar' => 'Boolean'
    );
    
    private static $defaults = array(
        'ShowSidebar' => true
    );
}


class PageControllerSidebarExtension extends Extension {
    
    public function MainContentColumns() {
        return "col-xs-12";
        // return $this->ShowSidebar ? "col-sm-9" : "col-xs-12";
    }
    
    public function SidebarColumns() {
        return "hidden";
        // return $this->ShowSidebar ? "col-sm-3" : "hidden";
    }
}
