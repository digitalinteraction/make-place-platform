<% require javascript('maps/javascript/vue.min.js') %>
<% require javascript('maps/javascript/jquery-3.1.1.min.js') %>
<% require javascript('maps/javascript/map-page.js') %>



<%-- TODO: Get working with themedCSS --%>
<% require css('maps/css/maps.css') %>



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


<% require javascript('https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js') %>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={$SiteConfig.MapApiKey}&amp;callback=setupMap">
</script>
