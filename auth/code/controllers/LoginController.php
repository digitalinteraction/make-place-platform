<?php
/**
 *
 */
class LoginController extends ContentController {
    
    protected $ClassName = "LoginPage";
    protected $Title = "Login";
    protected $useBasicPage = false;
    
    private static $allowed_actions = array(
        "LoginForm",
        "RegisterForm",
        'activate',
        'registered',
        'emailsent'
    );
    
    
    
    /*
     *  Page Lifecycle
     */
    public function index() {
        
        $user = Member::currentUser();
        
        if ($user != null && $user->getCanInteract()) {
            return $this->redirect($this->getBackURL());
        }
        
        $this->LoginMode = Session::get('LoginMode');
        
        if ($this->LoginMode == null) {
            $this->LoginMode = 'Login';
        }
        
        return $this;
    }
    
    public function Link($action = null) {
        return Controller::join_links('login/', $action);
    }
    
    public function Layout() {
        return $this->renderWith("LoginPage");
    }
    
    public function getBackURL() {
        
        $url = $this->request->getVar("BackURL");
        return ($url != null)? $url : "home/";
    }
    
    
    
    
    
    public function getIsSimplePage() {
        
        return $this->useBasicPage;
    }
    
    
    
    
    /*
     *  Page Actions
     */
    public function emailsent() {
        
        // Get the email passed in a get var
        $email = $this->request->getVar("email");
        
        
        // Set the title and content for the page
        $this->Title = "Activation email sent";
        $this->Content = "<p> Thank you for registering, an email has been sent to '$email' to finish setting up your account. </p>";
        
        
        $this->useBasicPage = true;
        
        // Render the page
        return $this->renderWith("Page");
    }
    
    public function activate() {
        
        // Get the email and key from the get vars
        $email = $this->request->getVar("e");
        $key = $this->request->getVar("k");
        
        
        // If either aren't set, redirect away
        if ($email == null || $key == null) {
            return $this->redirect("registered");
        }
        
        return $this->activateEmail($email, $key);
    }
    
    public function activateEmail($email, $key) {
        
        // Get a matching registration for those parameters
        $register = Registration::get()->filter(array(
            "Key" => $key, "Member.Email" => $email, "Active" => true
        ))->first();
        
        
        // If the registration isn't correct, redirect away
        if ($register == null) {
            return $this->redirect("registered");
        }
        
        
        // If we've got to here then the parameters passed are correct
        
        // Mark this registration as used so it can't be used again
        $register->Active = false;
        $register->write();
        
        
        // Let the member interact with content
        $register->Member()->addInteraction();
        
        
        // Redirect to the success page
        return $this->redirect("registered");
    }
    
    public function registered() {
        
        // Set the title and content for the page
        $this->Title = "Registered";
        $this->Content =  "<p> You're all set! Your account has been created and verified, now you can vote and comment on videos </p>";
        $this->Content .= "<p> Log in with your new account <a href='login/'> here </a> </p>";
        
        
        $this->useBasicPage = true;
        
        
        // Render the page
        return $this->renderWith("Page");
    }
    
    
    
    
    /*
     *  Register Form
     */
    public function RegisterForm() {
        
        $registerMessage = "<label> By registering you agree to our <a href='/terms' target='_blank'>Terms & Conditions</a>.</label>";
        
        // The fields of the form
        $fields = FieldList::create([
            TextField::create("FirstName", "First Name")
                ->setAttribute('placeholder', 'John'),
            TextField::create("Surname", "Surname")
                ->setAttribute('placeholder', 'Doe'),
            EmailField::create("Email", "Email")
                ->setAttribute('placeholder', 'me@example.com'),
            PasswordField::create("Password", "Password")
                ->setAttribute('placeholder', '••••••••'),
            RecaptchaField::create('Captcha'),
            LiteralField::create("TsAndCs", $registerMessage)
        ]);
        
        // The submit action
        $actions = FieldList::create([
            FormAction::create("submitRegister")->setTitle("Register")
        ]);
        
        // The required fields
        $required = RequiredFields::create([
            "FirstName", "LastName", "Email"
        ]);
        
        // Create the form
        $form = Form::create($this, "RegisterForm", $fields, $actions, $required);
        
        // Return the form for rendering
        return $form;
    }
    
    public function submitRegister(array $data, Form $form) {
        
        Session::set('LoginMode', 'Register');
        
        
        // Get the fields from the request
        $firstName = $data["FirstName"];
        $lastName = $data["Surname"];
        $email = $data["Email"];
        $password = $data["Password"];
        $encodedEmail = urlencode($email);
        
        $member = null;
        
        
        // Check if a signed up member exists with that email
        $existing = Member::get()->filter("Email", $email)->first();
        if ($existing != null && $existing->getCanInteract()) {
            
            // If they do, pretend we sent an email to that address
            // TODO: what if they created an account before but didn't verify it?
            return $this->redirect("login/emailsent/?email=$encodedEmail");
        }
        else if ($existing && !$existing->getCanInteract()){
            
            $member = $existing;
        }
        else {
            
            // Create a new member with *no* permissions
            $member = Member::create();
        }
        
        // Update the member
        $member->Email = $email;
        $member->FirstName = $firstName;
        $member->Surname = $lastName;
        $member->changePassword($password);
        
        
        // Save the new member
        $member->write();
        
        
        // Disable previous registration attempts
        $previousAttempts = Registration::get()->filter([
            "MemberID" => $member->ID,
            "Active" => true
        ]);
        foreach ($previousAttempts as $attempt) {
            $attempt->Active = false;
            $attempt->write();
        }
        
        
        
        
        
        
        
        // Create a registration object, generating a unique key for it
        $register = Registration::create();
        $register->MemberID = $member->ID;
        $register->Active = true;
        $register->Key = $register->generateUniqueKey($member, "Registration", "Key");
        $register->write();
        
        
        // Use the registration to construct a unique url
        $getParams = http_build_query(array(
            "e" => $email, "k" => $register->Key
        ));
        
        $title = SiteConfig::current_site_config()->Title;
        
        // Get link for the site
        $link = Director::absoluteBaseURL();
        
        // Create an email with the registration key in it
        $email = Email::create()
            ->setTo($email)
            ->setSubject("$title Account Activation")
            ->setTemplate("ActivationEmail")
            ->populateTemplate(array(
                "FirstName" => $firstName,
                "LastName" => $lastName,
                "Email" => $email,
                "Link" => "$link",
                "ActivateLink" => "{$link}login/activate?$getParams"
            ));
        
        // Send the email
        $email->send();
        
        
        // Redirect to email sent page
        return $this->redirect("login/emailsent/?email=$encodedEmail");
    }
    
    
    
    
    /*
     *  Login Form
     */
    public function LoginForm() {
        
        $forgotten = "<p><a class='bubble' href='Security/lostpassword?BackURL={$this->getBackURL()}' target='_blank'> I forgot my password </a></p>";
        
        $fields = FieldList::create(array(
            TextField::create('Email','Email')
                ->setAttribute('placeholder', 'me@example.com'),
            PasswordField::create('Password', 'Password')
                ->setAttribute('placeholder', '••••••••'),
            CheckboxField::create('Remember','Remember me next time'),
            HiddenField::create('ReturnLink','ReturnLink')->setValue($this->getBackURL()),
            LiteralField::create('Forgot', $forgotten)
        ));
        
        
        $actions = FieldList::create(array(
            FormAction::create('submitLogin', 'Login')
        ));
        
        
        $required = RequiredFields::create(array(
            'Email', 'Password', 'ReturnLink'
        ));
        
        return Form::create($this, 'LoginForm', $fields, $actions, $required);
    }
    
    public function submitLogin(array $data, Form $form) {
        
        $email = $data['Email'];
        $password = $data['Password'];
        $remember = isset($data['Remember']);
        $back = isset($data['ReturnLink']) ? $data['ReturnLink'] : 'home/';
        
        Session::set('LoginMode', 'Login');
        
        
        if ($email == null) {
            $form->sessionMessage('Please enter your email', 'warn');
            return $this->redirectBack();
        }
        
        if ($password == null) {
            $form->sessionMessage('Please enter your password', 'warn');
            return $this->redirectBack();
        }
        
        /** @var Member */
        $member = Member::get()->filter('Email', $email)->first();
        
        if ($member == null || !$member->checkPassword($password)->valid()) {
            $form->sessionMessage('Incorrect email or password', 'warn');
            return $this->redirectBack();
        }
        
        $member->login($remember);
        
        return $this->redirect($back);
    }
}
