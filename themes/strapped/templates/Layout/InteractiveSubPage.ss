<div class="container">
    
    <div class="row">
        
        <div class="$MainContentColumns">
            
            <article>
                
                <div class="jumbotron">
                    <% include PageTitle %>
                </div>
                
                <div class="content"> $Content </div>
                
                $Form
                $CommentsForm
                
            </article>
            
            
            <hr>
            
            
            <div class="row">
                
                <div class="col-sm-6">
                    <p class="author lead">
                        – $Author
                        <br>
                        $Created.Long
                    </p>
                </div>
                
                <div class="col-sm-6">
                    <% include VotingControls %>
                </div>
                
            </div>
            
            
            <hr>
            
            $DisplayComments
            
            <% include CommentControls %>
            
        </div>
        
        <div class="$SidebarColumns">
            <% include Sidebar %>
        </div>
        
    </div>
    
</div>
