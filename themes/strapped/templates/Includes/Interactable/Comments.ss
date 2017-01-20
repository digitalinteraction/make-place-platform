<div class="comment-section">
    
    <h3> Comments ($GetComments.Count) </h3>
    
    <% loop $GetComments %>
        <div class="comment $FirstLast">
            $Me
        </div>
        
    <% end_loop %>
</div>
