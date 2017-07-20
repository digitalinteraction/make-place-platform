<%-- Renders a survey --%>
<% require themedCSS('survey') %>

<form id="form-$Handle" method="post" enctype="multipart/form-data" action="$SurveyUrl"  class="survey">
    
    <div class="general-description"> $Description </div>
    
    <div class="question-list">
        <% loop $Questions %>
            $Me
        <% end_loop %>
        
        <% if ResponseLat %>
            <input type="hidden" name="ResponseLat" value="$ResponseLat">
        <% end_if %>
        
        <% if ResponseLng %>
            <input type="hidden" name="ResponseLng" value="$ResponseLng">
        <% end_if %>
        
        <% if RedirectBack %>
            <input type="hidden" name="RedirectBack" value="1">
        <% end_if %>
        
        <input type="hidden" name="$SecurityTokenName" value="$SecurityTokenValue">
        <input type="hidden" name="SurveyID" value="$ID">
        
    </div>
    
    
    <div class="action-list">
        <%-- <% if ClearTitle %>
            <input type="submit" name="clear" value="$ClearTitle">
        <% end_if %> --%>
        
        <% if SubmitTitle %>
            <input type="submit" name="submit" value="$SubmitTitle">
        <% end_if %>
    </div>
    
</form>
