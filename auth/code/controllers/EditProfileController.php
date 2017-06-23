<?php

/** A controller for esiting the current member's details */
class EditProfileController extends ContentController {
    
    public $Content = "<p> Edit Profile Controller </p>";
    public $Title = "Edit Your Profile";
    public $ClassName = "EditProfilePage";
    
    /** The url actions on this controller */
    private static $allowed_actions = [
        'MemberEditForm'
    ];
    
    
    // Controller Lifecycle
    /** Constructs an EditProfileController */
    public function init() {
        
        parent::init();
        
        // Error if there is no member
        if (Member::currentUserID() == null) {
            return $this->httpError(404);
        }
        
        // Store the member for later use
        $this->Member = Member::currentUser();
    }
    
    /** The link to get to this controller */
    public function Link($actions = null) {
        return $this->join_links("me/edit", $actions);
    }
    
    /** Called to render the layour portion of the page */
    public function Layout() {
        return $this->renderWith("EditProfilePage");
    }
    
    
    
    // Editing Logic
    
    /** A form to edit a member */
    public function MemberEditForm() {
        
        // The form's fields
        $fields = FieldList::create([
            TextField::create("FirstName", "First Name")
                ->setValue($this->Member->FirstName),
            TextField::create("Surname", "Surname")
                ->setValue($this->Member->Surname),
            // FileField::create("ProfileImage", "Profile Image")
            //     ->setAttribute("accept", "image/*"),
        ]);
        
        // The submit button
        $actions = FieldList::create([
            FormAction::create("submitMemberEdit", "Submit")
        ]);
        
        // Makes fields required
        $required = RequiredFields::create([
            "FirstName", "Surname"
        ]);
        
        
        // Returns a new form for rendering
        return Form::create($this, "MemberEditForm", $fields, $actions, $required);
    }
    
    /** Called to submit an edit on the current member */
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
 
