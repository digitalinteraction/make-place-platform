$(document).ready(function() {
    
    $(window).resize(function() {
        $("html body.fill").css(
            "padding-bottom", $("footer").outerHeight(true)
        );
    }).resize();
    
    // Add google fonts asnychronoulsy so they don't slow down page load times
    // $('head').append('<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,500,700i,600+Montserrat" rel="stylesheet">');
});
 
