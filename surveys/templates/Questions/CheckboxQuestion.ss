
<div>
  <% loop OptionsArray %>
    <input class="$Up.Classes" type="checkbox" name="$Up.FieldName[]" value="$value"> $key <br>
  <% end_loop %>
</div>
