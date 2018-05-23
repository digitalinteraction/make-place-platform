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
        
        <% if not SiteConfig.LoginDisabled && SiteConfig.RegisterDisabled %>
            <hr>
            $LoginForm
        <% else_if not SiteConfig.RegisterDisabled && SiteConfig.LoginDisabled %>
            <hr>
            <h3> Register </h3>
            $RegisterForm
        <% else_if not SiteConfig.RegisterDisabled && not SiteConfig.LoginDisabled %>
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
        <% else %>
            <hr>
            <h4 class="text-center"> Sorry, Login is currently unavailable </h4>
        <% end_if %>
        
    </article>
    
</div>
