<%-- A general page of the website --%>

<%-- Standard bootstrap to keep everything inline --%>
<div class="container">
    <div class="row">
        <div class="$MainContentColumns">
    
            <article>
                <div class="jumbotron">
                    <h1 class="display-3">$Title</h1>
                    <div class="content lead">
                        $Content
                    </div>
                </div>
                
                $Form
                $CommentsForm
                
            </article>
            
            
            <!-- The calendar view, only for larger browsers -->
            <article class="hidden-xs hidden-sm">
                
                <h1 class="current-month text-center">
                    <a href="$PrevMonthLink"><i class="fa fa-long-arrow-left"></i></a>
                    $CurrentDateName
                    <a href="$NextMonthLink"><i class="fa fa-long-arrow-right"></i></a>
                </h1>
                
                
                <%-- The calendar table --%>
                <table class="calendar">
                    
                    <%-- The table's headings (each day of the week) --%>
                    <tr>
                        <th class="text-right"> Mon </th>
                        <th class="text-right"> Tue </th>
                        <th class="text-right"> Wed </th>
                        <th class="text-right"> Thur </th>
                        <th class="text-right"> Fri </th>
                        <th class="text-right"> Sat </th>
                        <th class="text-right"> Sun </th>
                    </tr>
                    
                    <%-- Loop through the calendar data to get each week of the month --%>
                    <% loop $CalData %>
                    <tr>
                            
                        <%-- Loop through each day of the week --%>
                        <% loop Days %>
                        <td class="text-center <%if not $Include%>not-current<% end_if %> <%if not $Empty%>has-event<% end_if %> $IsToday">
                        
                            <%-- The day of the month --%>
                            <p class="day"> $Day </p>
                            
                            <%-- Display the events on the day --%>
                            <% loop Events %>
                                
                                <p class="event" data-event-id="$ID" data-toggle="modal" data-target="#eventModal">
                                    $DisplayName
                                </p>
                                
                                <% if not $Last %> <hr/> <% end_if %>
                                
                            <% end_loop %>
                            
                            
                        </td>
                        <% end_loop %>
                    </tr>
                    <% end_loop %>
                    
                    
                </table>
                
            </article>
            
            
            <!-- The event list, only for mobile devices -->
            <article class="metro-content visible-xs visible-sm">
                
                <div class="event-list">
                    
                    <%-- Mobile title --%>
                    <h1> Upcoming Events </h1>
                    
                    <%-- Create a row for each event in a list form --%>
                    <% loop $EventList %>
                    <div class="event" data-event-id="$ID" data-toggle="modal" data-target="#eventModal">
                        <h3 class="date"> $Date.Day $Date.DayOfMonth $Date.ShortMonth </h3>
                        <p class="name">$DisplayName</p>
                        <% if not $Last %> <hr> <% end_if %>
                    </div>
                    
                    <% end_loop %>
                
                </div>
                
            </article>
            
        </div>
        
        
        <div class="$SidebarColumns">
            <% include Sidebar %>
        </div>
        
        
    </div>
</div>




<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                
                <%-- To be filled with the title --%>
                <h4 class="modal-title" id="eventModalLabel"></h4>
            </div>
            
            
            <%-- To be filled with content --%>
            <div class="modal-body"> </div>
            
        </div>
    </div>
</div>
