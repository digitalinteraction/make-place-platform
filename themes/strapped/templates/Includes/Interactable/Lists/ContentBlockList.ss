<div class="blocks-container">
    <h2 class="results">
        <% if Count > 1 %>
            Found $Count matches
        <% else_if Count = 1 %>
            Found 1 match
        <% else %>
            No matches found
        <% end_if %>
     </h2>


    <div class="block-list">
        <% loop Me %>
            
            $Me.WithLink.WithBottom.Minimal
            
        <% end_loop %>
    </div>
</div>
