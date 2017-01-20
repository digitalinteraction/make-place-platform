<div class="row">
    
    <% if Count = 0 %>
        <p class="no-results"> No matches found </p>
    <% end_if %>

    <!-- Loop through the lightbox images -->
    <% loop Me %>
        
        <%-- Create a grid item for each image, clickable using lightbox --%>
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <div class="archive-image">
                <div class="image-outer">
                    <a href="$Image.URL" data-lightbox="archive" data-title="$Name">
                        $Image.Fill(300, 300)
                    </a>
                </div>
            </div>
        </div>
    
    <% end_loop %>
    
</div>
