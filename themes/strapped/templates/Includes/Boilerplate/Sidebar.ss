<%-- A template to display the sidebar --%>

<%-- Don't show the sidebar on mobile devices --%>
<div class="hidden-sm hidden-xs">
	
	<%-- Loop through each widget --%>
	<% loop SidebarWidgets %>
		$Me
		<% if not Last %>
			<hr>
		<% end_if %>
	<% end_loop %>

</div>
