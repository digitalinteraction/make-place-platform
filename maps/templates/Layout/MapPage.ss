<% require javascript('maps/javascript/vue.min.js') %>
<% require javascript('maps/javascript/jquery-3.1.1.min.js') %>
<% require javascript('maps/javascript/map-page.js') %>



<%-- TODO: Get working with themedCSS --%>
<% require css('maps/css/maps.css') %>

<div class="fluid-container">
    
    <div class="row">
        
        <div class="$MainContentColumns">
            
            <article>
                
                <div class="page-top">
                    <% include PageTitle %>
                    <div class="content"> $Content </div>
                </div>
                
                <div id="map-app" data-center-lat="$StartLat" data-center-lng="$StartLng" data-center-zoom="$StartZoom">
                    <% if AddButton %>
                        <div class="add-button">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            $AddButton
                        </div>
                    <% end_if %>
                    <div id="map"></div>
                </div>
                
            </article>
            
        </div>
        
        <div class="$SidebarColumns">
            <% include Sidebar %>
        </div>
        
    </div>
    
</div>


<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={$SiteConfig.MapApiKey}&amp;callback=setupMap">
</script>
