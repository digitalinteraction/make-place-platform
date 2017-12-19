<% require css('public/css/auth.css') %>

<div class="container">
    
    <article class="content-card">
        
        <% if BackURL %>
            <p><a class="bubble" href="$BackURL" title="Go Back" class="back">
                <i class="fa fa-chevron-left"></i> Go Back
            </a></p>
        <% end_if %>
        
        <h1 class="title">
            $Title
        </h1>
        
        
        <%-- OAuth --%>
        <%-- <div class="oauth">
            
            <h4> Sign in with: </h4>
            
            <a class="facebook-auth" href="oauth/fb/login?BackURL=$BackURL.URLATT">
                <i class="fa fa-facebook-official" aria-hidden="true"></i>
                Facebook
            </a>
        </div> --%>
        
        <%-- <p class="or-section"> or </p> --%>
        
        <div class="form-tabs">
            
            <ul class="nav nav-tabs" role="tablist">
                
                <li role="presentation" class="<% if LoginMode = Login %>active<% end_if %>">
                    <a href="#login" aria-controls="login" role="tab" data-toggle="tab"> Login </a>
                </li>
                
                <li role="presentation" class="<% if LoginMode = Register %>active<% end_if %>">
                    <a href="#register" aria-controls="register" role="tab" data-toggle="tab"> Register </a>
                </li>
                
            </ul>
            
            <!-- Tab panes -->
            <div class="tab-content">
                
                <div role="tabpanel" class="tab-pane <% if LoginMode = Login %>active<% end_if %>" id="login">
                    $LoginForm
                </div>
                
                <div role="tabpanel" class="tab-pane <% if LoginMode = Register %>active<% end_if %>" id="register">
                    $RegisterForm
                </div>
                
            </div>
            
        </div>
        
    </article>
    
</div>
