<footer role="contentinfo">
	
	<div class="container">
		
		<%-- TODO: Add Logos --%>
		
		<p class="footer-menu lead">
			<% loop Menu(1) %>
				
				<a class="link $LinkingMode $FirstLast" label="$Title.XML" href="$Link">$MenuTitle.XML</a>
				
			<% end_loop %>
		</p>
		
		<p>&copy; $SiteConfig.Title $Now.Format(Y)</p>
		
	</div>

</footer>
