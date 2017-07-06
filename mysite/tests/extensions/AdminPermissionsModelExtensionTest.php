<?php

class AdminPermTestObject extends DataObject {
    private static $extensions = [ 'AdminModelExtension' ];
}

/** Tests AdminPermissionsModelExtension */
/** @group whitelist */
class AdminPermissionsModelExtensionTest extends SapphireTest {
  
    public $usesDatabase = true;
  
    public function setUp() {
        parent::setUp();
        
        // Create a member
        $this->someMember = Member::create([ "Email" => "admin.perm.test@email.com" ]);
        $this->someMember->write();
        $this->object = AdminPermTestObject::create();
        
        // Create a group with cms access
        $group = Group::create([ 'Title' => 'Authors', 'Code' => 'authors' ]);
        $group->write();
        Permission::create(['Code' => 'CMS_ACCESS_CMSMain', 'Type' => 1, 'GroupID' => $group->ID])->write();
        
        // Add member to group
        $this->someMember->addToGroupByCode('authors');
        
        // Log in the member
        $this->someMember->logIn();
    }
    
    public function testCanView() {
        $this->assertTrue($this->object->canView());
    }
    
    public function testCanEdit() {
        $this->assertTrue($this->object->canView());
    }
    
    public function testCanCreate() {
        $this->assertTrue($this->object->canCreate());
    }
    
    public function testCanDelete() {
        $this->assertTrue($this->object->canDelete());
    }
}
