<div class="survey-response">
<% loop Response.Values %>
    <label class="key"> $Question.Name </label>
    <p class="value"> $Rendered </p>
<% end_loop %>
</div>
