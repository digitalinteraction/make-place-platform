<%-- Image Comment --%>

<div class="row">
    
    <div class="col-md-2 col-sm-3 col-xs-12">
        <p class="author">
            $Author.FirstName $Author.Surname
        </p>
        
        <p class="created"> $Created.Ago </p>
        
    </div>
    
    <div class="col-md-10 col-sm-9 col-xs-12">
        
        <%-- <div class="image-comment" style="background-image: url($URL)"> </div> --%>
        <audio class="comment" controls>
            <source src="$URL" type="audio/mpeg">
        </audio>
        
    </div>
    
</div>

<hr>
