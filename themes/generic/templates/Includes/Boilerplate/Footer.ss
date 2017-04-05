<footer>
    
    <div class="container">
        <p class="links">
            
            <% cached 'FooterLinks' %>
            
            <span class="copyright"> $SiteConfig.Title $Now.Format(Y) &copy; </span>
            
            
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
    </div>
    
</footer>
