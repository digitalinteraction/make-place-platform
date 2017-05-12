<div class="survey-response">
<% loop Response.Values %>
    <% if not Question.HiddenQuestion %>
        <label class="key"> $Question.Label </label>
        <p class="value"> $Rendered </p>
    <% end_if %>
<% end_loop %>
</div>
