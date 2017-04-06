<% require themedCSS('survey') %>


<% require css('maps/css/maps.css') %>
<% require css('maps/css/leaflet.css') %>
<% require css('maps/css/marker-cluster.css') %>


<div id="map-app">
    
    
    <div id="map-cancel-button" class="">
        <p class="web button red hidden-xs"> <i class="icon fa fa-ban"></i> Cancel </p>
        <p class="button mobile visible-xs"> </p>
    </div>
    
    <div id="map-overlay" class=""> <p class="message"></p></div>
    
    <div id="map-detail" class="">
        <h2 class="title">
            <span class="text"></span>
            <i class="close-button fa fa-times"></i>
        </h2>
        <div class="inner"></div>
    </div>
    
    <div id="map-controls" class="active">
        <h2 class="title"> Customise </h2>
    </div>
    
    <div id="mobile-buttons" class="active visible-xs">
        <p class="button actions"></p>
        <p class="button controls"></p>
    </div>
    
    <div id="map-actions" class="active">
        <p id="survey-component-1" class="action green">
          <i class="icon fa fa-info-circle"></i>Add Response
        </p>
    </div>
    
    
    
    <%-- An element to render the map into --%>
    <div id="map"></div>
    
</div>


<% if Tileset = 'Google' %>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={$SiteConfig.MapApiKey}&amp;">
    </script>
<% end_if %>


<script data-main="maps/javascript/map.js" src="maps/javascript/libs/require.js"></script>
