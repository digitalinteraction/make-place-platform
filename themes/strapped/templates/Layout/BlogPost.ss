<div class="container">
    
    <div class="row">
        
        <div class="$MainContentColumns">
            
            <div class="jumbotron">
                
                <% include PageTitle %>
                
                <p>
                    <i class="fa fa-fw fa-newspaper-o" aria-hidden="true"></i>
                    $PublishDate.Day, $PublishDate.Long $PublishDate.Time
                </p>
            </div>
            
            <div class="content">
                
                <% if $FeaturedImage %>
                    <p class="featured-image">$FeaturedImage.setWidth(795)</p>
                <% end_if %>
                
                $Content
            </div>
            
        </div>
        
        <div class="$SidebarColumns">
            <% include Sidebar %>
        </div>
        
    </div>
    
</div>
