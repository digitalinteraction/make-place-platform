<div class="content-block-item">
    <div class="block-inner">
        <% if AddLink %> <a href="$Link"> <% end_if %>
            
            <% if IsMinimal && Image %>
                <img class="block-image" src="$Image.URL" class="block-image" alt="$Content.Title">
            <% else %>
                <div class="block-content"> $Content </div>
            <% end_if %>
        
        <% if AddLink %> </a> <% end_if %>
        
        <% if AddOptions %>
            <p class="options">
                <a class="action view" href="$Link"> $Title </a>
            </p>
        <% end_if %>
        
    </div>
</div>
