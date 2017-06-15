<% require css('auth/css/auth.css') %>

<div class="container">
    
    <div class="row">
        
        <div class="col-md-3 col-sm-4">
            <article class="content-card profile-card">
                <div class="image" style="background-image:url($Member.ProfileImageUrl)">
                </div>
                <h3 class="title"> $Member.Name </h3>
                <div class="action-list">
                    <a class="action blue with-icon" href="me/edit/">
                        <span class="icon">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </span>
                        Edit Profile
                    </a>
                    <a class="action red with-icon" href="Security/logout/?BackURL=home%2F">
                        <span class="icon">
                            <i class="fa fa-sign-out" aria-hidden="true"></i>
                        </span>
                        Logout
                    </a>
                </div>
            </article>
        </div>
        
        
        <div class="col-md-9 col-sm-8">
            <article class="content-card">
                <h2 class="title"> My Messages </h2>
                <p class="light-text">
                    <i class="fa fa-frown-o" aria-hidden="true"></i>
                    No messages to show
                </p>
            </article>
        </div>
        
    </div>
    
</div>
