<div class="video-list grid">
    <div class="row">
        
    <% if Videos.Count = 0 %>
        
        <p class="no-results"> No matches found </p>
        
    <% end_if %>
    
    <% loop Videos %>
        
        <% if $Data.thumb %>
        
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                
                <a href="$Info.Link?BackURL=$Top.BackURL.URLATT">
                    
                    <div class="video-item $FirstLast">
                        
                        <% if Info.NoVideo %>
                            <div class="video-preview" style="background-image:url($ThemeDir/images/no-video.svg)"></div>
                        <% else %>
                            <div class="video-preview" style="background-image:url($Top.BootleggerProxy($Data.thumb)); $Info.Transform"></div>
                        <% end_if %>
                        
                        <div class="video-info">
                            <p class="video-title"> $Info.Title </p>
                            <p class="video-detail"> $Author </p>
                        </div>
                    </div>
                    
                </a>
                
            </div>
        
        <% end_if %>
        
    <% end_loop %>
    </div>
</div>
