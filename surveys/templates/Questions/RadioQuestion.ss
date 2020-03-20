
<% loop OptionsArray %>
  <div class="radio">
    <label> 
      <input type="radio" id="$value" name="$Up.FieldName" value="$value">
      $key
    </label>
  </div>
<% end_loop %>
