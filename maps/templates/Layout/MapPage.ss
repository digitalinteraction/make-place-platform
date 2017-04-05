<% require themedCSS('survey') %>


<% require css('maps/css/maps.css') %>
<% require css('maps/css/leaflet.css') %>
<% require css('maps/css/marker-cluster.css') %>



<%-- <div class="page-top">
    <% include PageTitle %>
    <div class="content"> $Content </div>
</div> --%>

<div id="map-app">
    
    
        
    <div id="map-detail" class="">
        <h1 class="title">
            <span class="text"></span>
            <i class="close-button fa fa-times"></i>
        </h1>
        <div class="inner"></div>
    </div>
    
    <div id="map-controls" class=""></div>
    
    <div id="map-actions" class=""></div>
    
    
    
    <%-- An element to render the map into --%>
    <div id="map"></div>
    
    
    <%-- An element to hold popovers --%>
    <div class="popover-container">
        <%-- ... --%>
    </div>
</div>

<% if Tileset = 'Google' %>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={$SiteConfig.MapApiKey}&amp;">
        // callback=setupMap
    </script>
<% end_if %>


<script data-main="maps/javascript/map.js" src="maps/javascript/libs/require.js"></script>
