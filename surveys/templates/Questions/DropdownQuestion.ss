
<div class="select-wrap">
  <select class="$Classes" name="$FieldName" value="$Value" placeholder="$Placeholder">
    <option disabled selected> Please select </option>
    <% loop OptionsArray %>
      <option value="$value"> $key </option>
    <% end_loop %>
  </select>
</div>
