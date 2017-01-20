<%-- Displays a summary of a passed blog post --%>

<article class="post-summary">
    
    <h2> <a href="$Link" title="$MenuTitle.XML">
        $MenuTitle
    </a> </h2>
    <p class="lead"> $PublishDate.Day, $PublishDate.Long $PublishDate.Time </p>
    
    <% if $FeaturedImage %>
        <div class="content">
            <p> $FeaturedImage.setWidth(495) </p>
        </div>
    <% end_if %>
    
    <% if $Summary %>
        <p>$Summary </p>
    <% else %>
        <p>$Excerpt.. </p>
    <% end_if %>
    
    <p class="text-right">
        <a href="$Link" title="$MenuTitle.XML">
            Keep Reading
            <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
        </a>
    </p>
    
    
    <% if not Last %> <hr> <% end_if %>
    
</article>
