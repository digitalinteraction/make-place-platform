<div class="container">
    
    <div class="row">
        
        <div class="$MainContentColumns">
            
            <div class="jumbotron">
                <% include PageTitle %>
            </div>
            
            <div class="lead">
                $Content
            </div>
            
            $Form
            $CommentsForm
            
            <hr>
            
            <%-- The actual blog post summaries --%>
            <% if $PaginatedList.Exists %>
                <% loop $PaginatedList %>
                    <% include PostSummary %>
                <% end_loop %>
            <% else %>
                <p><%t Blog.NoPosts 'There are no posts' %></p>
            <% end_if %>
            
            <% with $PaginatedList %>
                <% include Pagination %>
            <% end_with %>
            
            
        </div>
        
        <div class="$SidebarColumns">
            <% include Sidebar %>
        </div>
        
    </div>
    
</div>
