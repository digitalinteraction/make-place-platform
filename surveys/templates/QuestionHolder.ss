<% if not HiddenQuestion %>
        
    <div class="survey-question $ClassName">
        
        <% if Label %>
            <label for="$Handle">$Label</label>
        <% end_if %>
        
        $RenderField
        
        <% if Description %>
            <p class="description">$Description</p>
        <% end_if %>
        
    </div>

<% end_if %>
