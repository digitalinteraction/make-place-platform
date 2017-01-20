<%-- A common voting interface, designed to be used with a class extended with InteractableDatExtension --%>
<%-- Uses ajax-links.js perform a GET and reload the section asynchronously --%>

<div class="voting-section ajax-section" data-url-base="$Link">
    
    <p class="vote-message"> $Message </p>
    
    <%-- A progress bar to show the vote distribution --%>
    <div class="progress progress-bar-danger">
        <div class="progress-bar progress-bar-success" style="width: {$DataObj.AgreePercentage}%">
        </div>
    </div>
    
    <%-- A breakdown of how people voted --%>
    <p class="voting-detail text-center lead">
        $DataObj.AgreeCount
        
        <% if $DataObj.AgreeCount == 1 %> person
        <% else %> people
        <% end_if %>
        agrees
    </p>
    
    <% if $CurrentMember && $CurrentMember.CanInteract %>
        
        
        <p class="text-center">
            
            <a href="$VoteUpURL" class="vote agree $Agreed ajax">
                <i class="fa fa-check" aria-hidden="true"></i>Agree
            </a>
            
            <a href="$VoteDownURL"class="vote disagree $Disagreed ajax">
                <i class="fa fa-times" aria-hidden="true"></i>Disagree
            </a>
            
        </p>
    
    <% else %>
        <p class="login-register"> <% include LoginRegister Back=$ReturnLink.URLATT %> to vote </p>
    <% end_if %>
    
</div>
