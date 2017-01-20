<%-- Loop the first 3 events --%>
<% loop $Events.Limit(3) %>
    
    <%-- Display the event --%>
    <div class="event">
        
        <p class="date"> $Date.Nice </p>
        <p class="info"> $DisplayName </p>
        
        <%-- Add horizontal rules between the events --%>
        <% if not Last %> <hr/> <% end_if %>
    </div>
    
<% end_loop %>

<% if not Events || Events.Count = 0 %>
    <p> No Upcoming Events </p>
<% end_if %>

<%-- Clearfix the columns --%>
<div style="clear: both"></div>

<%-- If there are more than 5 events, show a calendar button to see more on the calendar page --%>
<p class="button-outer text-center">
    <a href="calendar" class="green-button">
        View Calendar <i class="fa fa-calendar" aria-hidden="true"></i>
    </a>
</p>
