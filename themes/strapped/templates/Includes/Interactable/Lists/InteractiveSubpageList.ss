<h2 class="results">
    <%-- <% if Count > 1 %> Found $Count matches
    <% else_if $Count = 1 %> Found 1 match
    <% else %> No matches found
    <% end_if %> --%>
</h2>


<div class="page-list">
    <div class="row">
    <% loop Me %>
        
        <div class="col-sm-4 col-xs-12">
            <a href="$Link">
                <div class="page-item">
                    
                    <% if $Image %>
                        <%-- <img src="$Image.SetHeight(180).URL" alt="$Image.Title" class="page-image"> --%>
                        <div class="image-holder">
                            <div class="page-image" style="background-image: url($Image.URL)"></div>
                        </div>
                    <% else %>
                        <div class="page-summary">
                            
                            <p class="inner">
                                &quot;<em>$Content.Summary(20, true)</em>&quot;
                            </p>
                        </div>
                    <% end_if %>
                    
                    <div class="page-info">
                        <h4 class="card-title text-center"> $MenuTitle </h4>
                        <p class="card-text text-center"> $Author </p>
                    </div>
                    
                </div>
            </a>
        </div>
        
    <% end_loop %>
    </div>
</div>
