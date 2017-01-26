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
                    <div class="col-xs-6">
                        $Survey
                    </div>
                    
                </div>
                
                $Form
                $CommentsForm
                
            </article>
            
        </div>
        
        <div class="$SidebarColumns">
            <% include Sidebar %>
        </div>
        
    </div>
    
</div>
