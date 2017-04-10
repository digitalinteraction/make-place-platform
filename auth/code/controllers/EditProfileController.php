<?php

/** ... */
class EditProfileController extends ContentController {
    
    public $Content = "<p> Edit Profile Controller </p>";
    public $Title = "Edit Your Profile";
    public $ClassName = "EditProfilePage";
    
    private static $allowed_actions = [
        'MemberEditForm'
    ];
    
    public function Link($actions = null) {
        return $this->join_links("me/edit", $actions);
    }
    
    public function init() {
        
        parent::init();
        
        if (Member::currentUserID() == null) {
            return $this->httpError(404);
        }
        
        $this->Member = Member::currentUser();
        
        // ...
    }
    
    public function Layout() {
        return $this->renderWith("EditProfilePage");
    }
    
    
    
    
    public function MemberEditForm() {
        
        $fields = FieldList::create([
            TextField::create("FirstName", "First Name")
                ->setValue($this->Member->FirstName),
            TextField::create("Surname", "Surname")
                ->setValue($this->Member->Surname),
            PasswordField::create("Password", "Password"),
            DropdownField::create("Something", "Something", ["A", "B", "C"]),
            FileField::create("ProfileImage", "Profile Image"),
        ]);
        
        $actions = FieldList::create([
            FormAction::create("submitMemberEdit", "Submit")
        ]);
        
        $required = RequiredFields::create([]);
        
        
        return Form::create($this, "MemberEditForm", $fields, $actions, $required);
    }
    
    public function submitMemberEdit($data, Form $form) {
        
        // ...
    }
}
 
