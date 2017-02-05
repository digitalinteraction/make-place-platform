<div class="container">
    
    <div class="row">
        
        <div class="$MainContentColumns">
            
            <article>
                
                <div class="jumbotron">
                    <% include PageTitle %>
                </div>
                
                <div class="content">
                    $Content
                </div>
                
                <div class="row">
                    <div class="col-sm-6">
                        $Survey.WithRedirect
                    </div>
                </div>
                
            </article>
            
        </div>
        
        <div class="$SidebarColumns">
            <% include Sidebar %>
        </div>
        
    </div>
    
</div>
