<div class="user-story-item">
    <div class="story-inner">
        <% if AddLink %> <a href="$Link"> <% end_if %>
        
        <p class="story"><span class="who heading">$SiteConfig.UserStoryWho </span>
            <span class="who value">  $Who, </span> <br>
            <span class="what heading"> $SiteConfig.UserStoryWhat </span>
            <span class="what value">  $What </span> <br>
            <span class="why heading"> $SiteConfig.UserStoryWhy </span>
            <span class="why value">  $Why</span></p>
        
        <% if AddLink %> </a> <% end_if %>
        
        <% if ShowControls %>
            <p class="options">
                <a class="action view" href="$Link">
                    <i class="fa fa-television" aria-hidden="true"></i> View
                </a>
                <% if ShowEdit %>
                    <a class="action edit" href="user/story/edit/$ID">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                    </a>
                <% end_if %>
            </p>
        <% end_if %>
    </div>
</div>
