<?php

class PermsFieldExtension extends Extension {
  
    public function viewPerms() {
        return [
            'Anyone' => 'Anyone',
            'NoOne' => 'No one',
            'Member' => 'Any Member',
            'Group' => 'Groups (select below)'
        ];
    }
    
    public function makePerms() {
        return [
            'NoOne' => 'No one',
            'Member' => 'Any Member',
            'Group' => 'Groups (select below)'
        ];
    }
  
    public function groupsMap() {
        
        // Listboxfield values are escaped, use ASCII char instead of &raquo;
        $map = array();
        foreach(Group::get() as $group) {
            $map[$group->ID] = $group->getBreadcrumbs(' > ');
        }
        asort($map);
        return $map;
    }
  
  
}
