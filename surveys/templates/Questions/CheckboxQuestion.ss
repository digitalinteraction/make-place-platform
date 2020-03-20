
<% loop OptionsArray %>
  <div class="checkbox">
    <label>
      <input type="checkbox" name="$Up.FieldName[]" value="$value">
      $key
    </label>
  </div>
<% end_loop %>