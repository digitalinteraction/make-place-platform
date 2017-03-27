<footer class="bottom" role="contentinfo">
    
    <div class="container">
        
        <%-- TODO: Add Logos --%>
        
        <p class="footer-menu lead">
            
            <% if CurrentMember %>
                <a class="link" label="Logout" href="Security/logout?BackURL=$Link.URLATT">Logout</a>
            <% else %>
                <a class="link" label="Login" href="login?BackURL=$Link.URLATT">Login</a>
            <% end_if %>
            
            <% cached 'FooterLinks' %>
                
                <% with $Page(terms) %>
                    <% if Me %>
                        <a class="link" label="$Title.XML" href="$Link">$MenuTitle.XML</a>
                    <% end_if %>
                <% end_with %>
                
                <% with $Page(privacy) %>
                    <% if Me %>
                        <a class="link" label="$Title.XML" href="$Link">$MenuTitle.XML</a>
                    <% end_if %>
                <% end_with %>
                
                <% with $Page(contact) %>
                    <% if Me %>
                        <a class="link" label="$Title.XML" href="$Link">$MenuTitle.XML</a>
                    <% end_if %>
                <% end_with %>
                
            <% end_cached %>
            
        </p>
        
        <p class="copy">&copy; $SiteConfig.Title $Now.Format(Y)</p>
        
    </div>
    
</footer>
