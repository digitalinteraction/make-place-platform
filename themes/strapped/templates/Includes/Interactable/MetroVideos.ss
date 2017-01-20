<div class="map-container">
    
    <svg id="metro-map" viewBox="0 0 2375 1116">
        <% include MetroMap %>
        
        <g class="videos-container">
            <% loop $Pins %>
                <g class="video-pin"
                    data-metro-x="$x"
                    data-metro-y="$y"
                    data-count="$videos.Count"
                    data-ids="<%loop $videos %>$Info.ID<%if not Last%>,<%end_if%><%end_loop%>">
                    <g transform="translate(-5 -70)">
                        $Top.RenderSvg('images/video-bubble-number')
                    </g>
                </g>
            <% end_loop %>
        </g>
    </svg>
    
    <p class="map-controls non-selectable">
        <span class="control toggle-labels"> <i class="fa fa-tags" aria-hidden="true"></i> </span>
        <span class="control zoom-in"> <i class="fa fa-plus" aria-hidden="true"></i> </span>
        <span class="control zoom-out"> <i class="fa fa-minus" aria-hidden="true"></i> </span>
    </p>
    
    <div class="map-sidedraw" style="display:none">
        <p class="draw-title">
            <span class="text"> 2 Videos Found </span>
            <span class="close-draw"> <i class="fa fa-times" aria-hidden="true"></i> </span>
        </p>
        <div class="draw-content"></div>
    </div>
    
</div>

<div class="mobile-message">
    <p><i class="fa fa-repeat fa-spin" aria-hidden="true"></i></p>
    <p>Please rotate for the full experience</p>
</div>
