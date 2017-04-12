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
            // FileField::create("ProfileImage", "Profile Image")
            //     ->setAttribute("accept", "image/*"),
        ]);
        
        $actions = FieldList::create([
            FormAction::create("submitMemberEdit", "Submit")
        ]);
        
        $required = RequiredFields::create([
            "FirstName", "Surname"
        ]);
        
        
        return Form::create($this, "MemberEditForm", $fields, $actions, $required);
    }
    
    public function submitMemberEdit($data, Form $form) {
        
        // Update the profile from the data, getting any errors that occur
        $errors = $this->Member->updateProfile($data);
        
        
        // If there are any errors, present them to the user
        if (count($errors) > 0) {
            
            foreach ($errors as $error) {
                $form->addErrorMessage($error, "Required Field", "error");
            }
            
            // Return to the form to show the errors
            return $this->redirectBack();
        }
        
        
        // If successful, return to the user page
        // TODO: Add a splash message?
        return $this->redirect("me/");
    }
}
 
