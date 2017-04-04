<article class="post-summary content-card">
    
    <h2 class="post-title"><a href="$Link" title="$MenuTitle.XML"> $MenuTitle </a></h2>
    
    <p class="light-text"> $PublishDate.Day, $PublishDate.Long $PublishDate.Time </p>
    
    <% if FeaturedImage %>
        <div class="content">
            <p> $FeaturedImage.setWidth(495) </p>
        </div>
    <% end_if %>
    
    <% if Summary %>
        <p> $Summary </p>
    <% else %>
        <p> $Excerpt.. </p>
    <% end_if %>
    
    <p class="text-right">
        <a href="$Link" title="$MenuTitle.XML">
            Keep Reading
            <i class="fa fa-long-arrow-right"></i>
        </a>
    </p>
    
</article>
