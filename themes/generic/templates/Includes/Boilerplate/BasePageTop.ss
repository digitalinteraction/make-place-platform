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
	<% require themedCSS('bootstrap.min') %>
	<% require themedCSS('font-awesome.min') %>
	
	<%-- <% require themedCSS('generic') %> --%>
	<% require css('public/css/generic.css') %>
	
	
	<%-- Js to go to the top of the stack --%>
	
	<%-- Webpack compiled --%>
	<% require javascript('public/js/manifest.js') %>
	<% require javascript('public/js/vendor.js') %>
	
	<%-- Our scripts --%>
	<% require javascript('themes/generic/javascript/jquery.min.js') %>
	<% require javascript('themes/generic/javascript/script.js') %>
	<% require javascript('themes/generic/javascript/bootstrap.min.js') %>
	
	
	
	<!-- The favicon -->
	<link rel="shortcut icon" href="$ThemeDir/images/favicon.png" />
	
</head>
<body class="$ClassName $ShouldFillScreen" <% if $i18nScriptDirection %>dir="$i18nScriptDirection"<% end_if %>>
