<% require css('public/css/maps.css') %>
<%-- <% require javascript('public/js/maps.js') %> --%>

<div id="map-app" v-cloak></div>

<% if Tileset = 'Google' %>
  <script src="https://maps.googleapis.com/maps/api/js?key={$SiteConfig.MapApiKey}&amp;">
  </script>
<% end_if %>


<script src="/public/js/maps.js"></script>
