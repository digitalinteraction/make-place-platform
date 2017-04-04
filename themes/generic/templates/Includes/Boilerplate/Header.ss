<header>
    <nav class="navbar navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a href="/" class="navbar-brand">
                    <% if SiteConfig.BrandLogo %>
                        <img src="$SiteConfig.BrandLogo.URL" alt="$SiteConfig.Title">
                    <% else %>
                        <img src="$ThemeDir/images/brand.png" alt="$SiteConfig.Title">
                    <% end_if %>
                </a>
            </div>
            
            <ul class="nav navbar-nav navbar-right">
                
                <% loop Menu(1) %>
                    <% if $URLSegment != 'home' %>
                        <li class="menu-item">
                            <a href="$Link" class="<% if $LinkingMode != 'link' %> active <% end_if %>">
                                $MenuTitle
                            </a>
                        </li>
                    <% end_if %>
                <% end_loop %>
                
            </ul>
        </div>
        
    </nav>
</header>
