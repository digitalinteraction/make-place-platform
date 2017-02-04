<%-- <div class="survey-response"> --%>
<% loop Response.Values %>
    <label> $Question.Name </label>
    <p> $Rendered </p>
<% end_loop %>
<%-- </div> --%>
