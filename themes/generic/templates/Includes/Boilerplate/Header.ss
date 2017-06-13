<header>
    <nav class="navbar navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header-collapse" aria-expanded="false"><i class="fa fa-bars" aria-hidden="true"></i></button>
                <a href="/" class="navbar-brand">
                    <% if SiteConfig.BrandLogo %>
                        <img src="$SiteConfig.BrandLogo.URL" alt="$SiteConfig.Title">
                    <% else %>
                        <% include Brand %>
                    <% end_if %>
                </a>
            </div>
            
            <div class="collapse navbar-collapse" id="header-collapse">
                <ul class="nav navbar-nav navbar-right">
                    
                    <% loop Menu(1) %>
                        <li class="menu-item">
                            <a href="$Link" class="<% if $LinkingMode != 'link' %> active <% end_if %>">
                                $MenuTitle
                            </a>
                        </li>
                    <% end_loop %>
                    
                    
                    <% if CurrentMember %>
                        
                        <li class="menu-item">
                            <a href="me/" title="profile">
                                <i class="fa fa-user" aria-hidden="true"></i>
                                $CurrentMember.Name
                            </a>
                        </li>
                        
                    <% else %>
                        
                        <li class="menu-item">
                            <a href="login?BackURL=$Link.URLATT" title="login"> Login </a>
                        </li>
                        
                    <% end_if %>
                    
                </ul>
            </div>
        </div>
        
    </nav>
</header>
