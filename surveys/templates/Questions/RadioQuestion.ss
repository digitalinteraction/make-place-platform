
<div >
  <% loop OptionsArray %>
    <label> 
    <input class="$Up.Classes" type="radio" id="$value" name="$Up.FieldName" value="$value">
    $key </label><br>
  <% end_loop %>
</div>
