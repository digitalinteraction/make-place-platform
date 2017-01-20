<% if $CurrentMember && $CurrentMember.CanInteract %>
    <div class="row">
        <div class="col-md-10 col-xs-9 col-md-push-2 col-xs-push-3">
            $CommentForm
        </div>
    </div>
<% else %>
    <p class="login-register"> <% include LoginRegister Back=$ReturnLink.URLATT %> to comment </p>
<% end_if %>
