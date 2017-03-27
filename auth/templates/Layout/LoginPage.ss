<% require css('auth/css/auth.css') %>

<% if IsSimplePage %>
    
    <div class="container">
        <article>
            
            <div class="jumbotron">
                <% include PageTitle %>
            </div>
            
            <div class="content"> $Content </div>
            
        </article>
    </div>
    
<% else %>
    
    <div class="container">
        
        <article>
            
            <h1 class="title">
                <% if BackURL %>
                    <a href="$BackURL" title="Go Back" class="back"><i class="fa fa-chevron-left"></i></a>
                <% end_if %>
                $Title
            </h1>
            
            
            <%-- OAuth --%>
            <div class="oauth">
                
                <h4> Sign in with: </h4>
                
                <a class="facebook-auth" href="oauth/fb/login?BackURL=$BackURL.URLATT">
                    <i class="fa fa-facebook-official" aria-hidden="true"></i>
                    Facebook
                </a>
            </div>
            
            <p class="or-section"> or </p>
            
            <div class="form-tabs">
                
                <ul class="nav nav-tabs" role="tablist">
                    
                    <% loop TabContent %>
                        <li role="presentation" class="$Active">
                            <a href="#$ID" aria-controls="$ID" role="tab" data-toggle="tab"> $Title </a>
                        </li>
                    <% end_loop %>
                    
                </ul>
                
                <!-- Tab panes -->
                <div class="tab-content">
                    
                    <% loop TabContent %>
                        <div role="tabpanel" class="tab-pane $Active" id="$ID">
                            <%-- <p class="message"> $Message </p> --%>
                            $Tab
                        </div>
                    <% end_loop %>
                    
                </div>
                
            </div>
            
        </article>
        
    </div>
    
<% end_if %>
