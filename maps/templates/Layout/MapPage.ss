<% require themedCSS('survey') %>


<% require css('maps/css/maps.css') %>
<% require css('maps/css/leaflet.css') %>
<% require css('maps/css/marker-cluster.css') %>



<%-- <div class="page-top">
    <% include PageTitle %>
    <div class="content"> $Content </div>
</div> --%>

<div id="map-app" data-center-lat="$StartLat" data-center-lng="$StartLng" data-center-zoom="$StartZoom">
    
    <%-- Add the actions --%>
    <div class="map-actions">
        <% loop Actions %>
            <p class="action $Handle $Colour">
                <i class="fa $Icon" aria-hidden="true"></i>
                <span class="text">$Name</span>
            </p>
        <% end_loop %>
    </div>
    
    
    <%-- An element to render the map into --%>
    <div id="map"></div>
    
    
    <%-- An element to hold popovers --%>
    <div class="popover-container">
        <%-- ... --%>
    </div>
</div>


<script
    src="https://maps.googleapis.com/maps/api/js?key={$SiteConfig.MapApiKey}&amp;">
    // callback=setupMap
</script>


<script data-main="maps/javascript/map.js" src="maps/javascript/libs/require.js"></script>
