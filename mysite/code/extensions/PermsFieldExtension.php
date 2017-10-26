<?php

/**
 * An extension for something to provide permission fields
 */
class PermsFieldExtension extends Extension {
    
    /** The permissions to view something */
    public function viewPerms() {
        return [
            'Anyone' => 'Anyone',
            'NoOne' => 'No one',
            'Member' => 'Any Member',
            'Group' => 'Groups (select below)'
        ];
    }
    
    /** The permissions to make/create something */
    public function makePerms() {
        return [
            'NoOne' => 'No one',
            'Member' => 'Any Member',
            'Group' => 'Groups (select below)'
        ];
    }
    
    /** Gets a map of Group's id to their name */
    public function groupsMap() {
        
        // Listboxfield values are escaped, use ASCII char instead of &raquo;
        $map = array();
        foreach(Group::get() as $group) {
            $map[$group->ID] = $group->getBreadcrumbs(' > ');
        }
        asort($map);
        return $map;
    }
    
    /** Check a permission with the value of a permission field & a set of groups */
    public function checkPerm($permission, DataList $groups = null) {
        
        // Always return true for 'Anyone' permissions
        if ($permission == 'Anyone') return true;
        
        // Always return false for 'NoOne' permissions
        if ($permission == 'NoOne') return false;
        
        
        // Get the current memeber
        $member = Member::currentUser();
        
        // If using group permissions, check those
        if ($permission == 'Group') {
            return $member != null && $member->inGroups($groups);
        }
        
        // Default to if the user is verified
        return $member != null && $member->getHasVerified();
    }
  
  
}
