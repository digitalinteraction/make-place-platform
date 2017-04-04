<div class="container">
    
    <article class="content-card">
        <% include PageTitle %>
        <div class="content"> $Content </div>
    </article>
    
    <% if PaginatedList.Exists %>
        <% loop $PaginatedList %>
            <% include PostSummary %>
        <% end_loop %>
    <% end_if %>
    
    <% with PaginatedList %>
        <% include Pagination %>
    <% end_with %>
    
</div>
