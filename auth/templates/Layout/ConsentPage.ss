<div class="container">
    <article class="content-card">
        <h1 class="title"> Your consent </h1>
        
        <% if $CurrentMember.ConsentStatus = 'Reject' %>
            
            <p> Our policies have changed since you last consented to them so we want  give you a change to review the new policies. We want to be open about the information we store and how we process that information. </p>
            
        <% else %>
            
            <p> You have previously rejected our policies and can now no longer use our site. </p>
            
            <p> If you have changed your mind and wish to do so, you can do that below </p>
            
        <% end_if %>
        
        <p> Unfortunately, if you do not consent you cannot continue to use this website. If you have any questions or you want to remove your data, you can contact us any time at $AdminLink. </p>
        
        <p> Here are our <a href="$TermsLink"> Terms & Conditions </a> </p>
        
        <p> Here is our <a href="$PrivacyLink"> Privacy Policy </a> </p>
        
        $ConsentForm
        
    </article>
</div>
