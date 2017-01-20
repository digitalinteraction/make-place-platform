<a href="$Info.Link">
    
    <div class="video-item $FirstLast">
        
        <% if Info.NoVideo %>
            <div class="video-preview" style="background-image:url($ThemeDir/images/no-video.svg)"></div>
        <% else %>
            <div class="video-preview" style="background-image:url($Info.BootleggerProxy($Data.thumb)); $Info.Transform"></div>
        <% end_if %>
        
        <div class="video-info">
            <p class="video-title"> $Info.Title </p>
            <p class="video-detail"> $Author </p>
        </div>
    </div>

</a>
