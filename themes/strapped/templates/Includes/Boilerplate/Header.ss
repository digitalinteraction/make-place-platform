<header class="top" role="banner">
    
    <nav class="navbar navbar-default navbar-static-top">
        
        <div class="container">
            
            <div class="row">
                
                <%-- The brand & strapline --%>
                <div class="col-sm-3 col-xs-10">
                    <a href="home/">
                        <img class="brand" alt="Brand" src="$ThemeDir/images/mock-brand.png" style="width: 100%;">
                        <p>$SiteConfig.Tagline</p>
                    </a>
                </div>
                
                <%-- The menu which collapses for mobile --%>
                <div class="col-sm-9 col-xs-2">
                    
                    <%-- Hamburger menu only for mobile --%>
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            
                            <% loop $Menu(1) %>
                                <li class="nav-item <% if $LinkingMode = 'current' %>active<%end_if%>">
                                <a class="nav-link" href="$Link" title="$Title.XML">$MenuTitle.XML</a>
                                </li>
                            <% end_loop %>
                            
                        </ul>
                        
                        
                    </div><!-- /.navbar-collapse -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </nav>
    
</header>
