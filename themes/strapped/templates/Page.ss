<% include BasePageTop %>

<% if $SiteConfig.IsPublic || $CurrentMember %>
	<% include Header %>
<% else %>
	<% include MinimalHeader %>
<% end_if %>

<div class="main" role="main">
    $Layout
</div>

<% if $SiteConfig.IsPublic || $CurrentMember %>
	<% include Footer %>
<% end_if %>

<% include BasePageBottom %>
