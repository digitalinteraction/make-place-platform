<form $FormAttributes style="width: 100%;">
    
    <div class="row">
        <%-- <div class="field-list"> --%>
            <% loop $Fields %>
                
                <% if $Type != 'hidden' %>
                    <div class="col">
                        <label> $Name </label>
                        $Field
                    </div>
                <% else %>
                    $Field
                <% end_if %>
            <% end_loop %>
        <%-- </div> --%>
        
        <%-- <div class="actions"> --%>
        <%-- <div class="col-sm-2"> --%>
            <% loop $Actions %>
                <div class="col-2">
                    <label></label>
                    $Field
                </div>
            <% end_loop %>
        <%-- </div> --%>
    </div>
    
</form>
