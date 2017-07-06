<?php
/**
 *  An extension for DataObjects which gives them the same permissions as pages
 *  (ie if the model is embedded in a page, it can be edited along side it)
 */
class AdminModelExtension extends Extension {
    
    public function canView($member = null) {
        return Permission::checkMember($member, 'CMS_ACCESS_CMSMain');
    }
    
    public function canEdit($member = null) {
        return Permission::checkMember($member, 'CMS_ACCESS_CMSMain');
    }
    
    public function canCreate($member = null) {
        return Permission::checkMember($member, 'CMS_ACCESS_CMSMain');
    }
    
    public function canDelete($member = null) {
        return Permission::checkMember($member, 'CMS_ACCESS_CMSMain');
    }
}
