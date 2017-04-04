<div class="container">
    
    <article class="content-card">
        
        <% include PageTitle %>
        
        <h3 class="light-text post-date">
            <i class="fa fa-fw fa-newspaper-o"></i>
            $PublishDate.Day, $PublishDate.Long $PublishDate.Time
        </h3>
        
        <div class="content">
            <% if FeaturedImage %>
                <p class="featured-image"> $FeaturedImage.setWidth(795) </p>
            <% end_if %>
            $Content
        </div>
        
    </article>
    
</div>
