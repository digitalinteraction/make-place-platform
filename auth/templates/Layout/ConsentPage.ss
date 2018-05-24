<% require css('public/css/auth.css') %>

<div class="container">
    <article class="content-card">
        <h1 class="title"> Your consent </h1>
        
        <% if $CurrentMember.getHasConsent %>
            
            <p> Thank you for consenting to our terms and privacy policy. We want to be as open as posible so you can review your consent at any time below. </p>
            
        <% else_if $CurrentMember.ConsentStatus == "Reject" %>
            
            <p> You have rejected our policies and can now no longer use our site. If you have changed your mind, you can do that below. </p>
            
        <% else %>
            
            <p> Our terms and privacy have recently changed since you last reviewed them. We want to be open about the information we store and how we process that information. </p>
            
        <% end_if %>
        
        <p> If you have any questions or you want us to remove your data, you can contact us any time at $AdminLink. Unfortunately, if you do not consent you cannot continue to use this website. </p>
        
        <p> Please review our <a href="$TermsLink"> Terms & Conditions </a> and <a href="$PrivacyLink"> Privacy Policy </a> </p>
        
        $ConsentForm
        
    </article>
</div>
