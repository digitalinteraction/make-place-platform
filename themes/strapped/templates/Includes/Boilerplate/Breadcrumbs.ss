<% if Count > 1 %>
    <p class="breadcrumbs">

        <% loop $Me %>
            
            <a href="$Link" class="crumb $FirstLast"> $MenuTitle </a>
            
            <% if not Last %>
                <span class="seperator"> / </span>
            <% end_if %>
            
        <% end_loop %>
        
    </p>
<% end_if %>
