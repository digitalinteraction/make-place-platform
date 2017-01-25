<%-- Renders a survey --%>
<% require themedCSS('survey') %>

<form id="form-$Handle" method="post" enctype="application/x-www-form-urlencoded" action="$SurveyUrl" method="post" class="survey">

    <div class="question-list">
        <% loop $Questions %>
            $Me
        <% end_loop %>
        
        <input type="hidden" name="$SecurityTokenName" value="$SecurityTokenValue">
        <input type="hidden" name="SurveyID" value="$ID">
        
    </div>
    
    
    <div class="action-list">
        <% if ClearTitle %>
            <input type="submit" name="clear" value="$ClearTitle">
        <% end_if %>
        
        <% if SubmitTitle %>
            <input type="submit" name="submit" value="$SubmitTitle">
        <% end_if %>
    </div>
    
</form>
