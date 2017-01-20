<div class="stories-container">
    <h2 class="results">
        <% if Count > 1 %>
            Found $Count matches
        <% else_if Count = 1 %>
            Found 1 match
        <% else %>
            No matches found
        <% end_if %>
     </h2>


    <div class="story-list">
        <% loop Me %>
            
            $Me.WithLink
            
        <% end_loop %>
    </div>
</div>
