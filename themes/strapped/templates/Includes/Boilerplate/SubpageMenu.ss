<% if Menu(2) && ShowSubmenu %>
    
    <header class="subpages">
        
        <div class="pre-menu"> $HeaderContent </div>
        
        
        <div class="row hidden-xs">
            <% loop Menu(2) %>
                
                <div class="col-sm-{$Up.SubmenuColumns}">
                    <a class="subpage $FirstLast" href="$Link" style="background-image: url($MenuIcon.URL)">
                        <p class="title"> $MenuTitle </p>
                    </a>
                </div>
                
            <% end_loop %>
        </div>
        
        <div class="visible-xs">
            
            <ul class="mobile-submenu">
                <% loop Menu(2) %>
                    <li><a href="$Link"> $MenuTitle <i class="fa fa-angle-double-right" aria-hidden="true"></i> </a></li>
                <% end_loop %>
            </ul>
            
        </div>
        
        <hr>
        
    </header>
    
<% end_if %>
