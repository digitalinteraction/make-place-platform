<!DOCTYPE html>

<!--
generic. A parameterised silverstripe theme
-->

<!--[if !IE]><!-->
<html lang="$ContentLocale">
<!--<![endif]-->
<!--[if IE 6 ]><html lang="$ContentLocale" class="ie ie6"><![endif]-->
<!--[if IE 7 ]><html lang="$ContentLocale" class="ie ie7"><![endif]-->
<!--[if IE 8 ]><html lang="$ContentLocale" class="ie ie8"><![endif]-->
<head>
	<% base_tag %>
	
	<title><% if $MetaTitle %>$MetaTitle<% else %>$Title<% end_if %> – $SiteConfig.Title</title>
	
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	$MetaTags(false)
	
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700+Jaldi" rel="stylesheet">
	
	<% require css('public/css/common.css') %>
	<% require css('public/css/theme.css') %>
	
	
	<%-- Js to go to the top of the stack --%>
	
	<%-- Webpack compiled --%>
	<% require javascript('public/js/manifest.js') %>
	<% require javascript('public/js/vendor.js') %>
	
	
	
	<!-- The favicon -->
	<% if SiteConfig.Favicon %>
		<link rel="shortcut icon" href="$SiteConfig.Favicon.URL" />
	<% else %>
		<link rel="shortcut icon" href="$ThemeDir/images/favicon.png" />
	<% end_if %>
	
</head>
<body class="$ClassName $ShouldFillScreen" <% if $i18nScriptDirection %>dir="$i18nScriptDirection"<% end_if %>>
