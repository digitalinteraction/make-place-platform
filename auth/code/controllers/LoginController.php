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
        
        $this->addRecaptchaJs();
        
        if ($user != null) {
            return $this->redirect($this->getBackURL());
        }
        
        $this->LoginMode = Session::get('LoginMode');
        
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
    
    public function getTabContent() {
        
        $mode = Session::get('LoginMode');
        if ($mode == null) {
            $mode = 'Login';
        }
        
        return ArrayList::create(array(
            array(
                "Title" => "Login",
                "Active" => $mode == 'Login' ? 'active' : '',
                "ID" => 'login',
                'Tab' => $this->LoginForm(),
                'Message' => 'Log in with Metro Futures'
            ),
            array(
                "Title" => "Register",
                "Active" => $mode == 'Register' ? 'active' : '',
                "ID" => 'register',
                'Tab' => $this->RegisterForm(),
                'Message' => 'Create a Metro Futures account'
            )
        ));
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
            "Key" => $key, "Member.Email" => $email, "Used" => false
        ))->first();
        
        
        // If the registration isn't correct, redirect away
        if ($register == null) {
            return $this->redirect("registered");
        }
        
        
        // If we've got to here then the parameters passed are correct
        
        // Mark this registration as used so it can't be used again
        $register->Used = true;
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
        $fields = FieldList::create(array(
            TextField::create("FirstName", "First Name")
                ->setAttribute('placeholder', 'John'),
            TextField::create("Surname", "Surname")
                ->setAttribute('placeholder', 'Doe'),
            EmailField::create("Email", "Email")
                ->setAttribute('placeholder', 'me@example.com'),
            PasswordField::create("Password", "Password")
                ->setAttribute('placeholder', '••••••••'),
            TextField::create("Postcode", "Postcode")
                ->setAttribute('placeholder', 'NE1'),
            LiteralField::create("TsAndCs", $registerMessage)
        ));
        
        // The submit action
        $actions = FieldList::create(array(
            FormAction::create("submitRegister")->setTitle("Register")
        ));
        
        // The required fields
        $required = RequiredFields::create(array(
            "FirstName", "LastName", "Email"
        ));
        
        // Create the form
        $form = Form::create($this, "RegisterForm", $fields, $actions, $required);
        
        // Use the captcha template & set the captcha key (defined in _ss_environment.php)
        $form->setTemplate("Forms/CaptchaForm");
        $form->CaptchaPublicKey = G_RECAPTCHA_PUBLIC;
        
        // Return the form for rendering
        return $form;
    }
    
    public function submitRegister(array $data, Form $form) {
        
        Session::set('LoginMode', 'Register');
        
        // Check the captcha passed
        $captchaResponse = $this->processRecaptcha($data, $form);
        if ($captchaResponse != null) {
            return $captchaResponse;
        }
        
        // Get the fields from the request
        $firstName = $data["FirstName"];
        $lastName = $data["Surname"];
        $email = $data["Email"];
        $password = $data["Password"];
        $encodedEmail = urlencode($email);
        
        
        // Check if a member exists with that email
        $existing = Member::get()->filter("Email", $email)->first();
        if ($existing != null) {
            
            // If they do, pretend we sent an email to that address
            // TODO: what if they created an account before but didn't verify it?
            return $this->redirect("login/emailsent/?email=$encodedEmail");
        }
        
        
        
        // Create a new member with *no* permissions
        $member = Member::create();
        $member->FirstName = $firstName;
        $member->Surname = $lastName;
        $member->Email = $email;
        $member->changePassword($password);
        
        // If set, add the postcode too
        if (isset($data["Postcode"])) {
            $member->Location = $data["Postcode"];
        }
        
        // Save the new member
        $member->write();
        
        
        // Create a registration object, generating a unique key for it
        $register = Registration::create();
        $register->MemberID = $member->ID;
        $register->Used = false;
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
            ->setSubject("$title account activation")
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
        
        $forgotten = "<p><a href='Security/lostpassword?BackURL={$this->getBackURL()}' target='_blank'> I forgot my password </a></p>";
        
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
