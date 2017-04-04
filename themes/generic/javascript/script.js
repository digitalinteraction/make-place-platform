$(document).ready(function() {
    
    // // Offset the bottom of the body by the footer because its positioned absolutely
    // $(window).resize(function() {
    //     $("html body").css("padding-bottom", $("footer").outerHeight(true) );
    //     $("html body").css("padding-top", $("header").outerHeight(true) );
    // }).resize();
    
    $(window).resize(function() {
        $("html body.fill").css(
            "padding-bottom", $("footer").outerHeight(true)
        );
    }).resize();
    
    // Add google fonts asnychronoulsy so they don't slow down page load times
    $('head').append('<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600i,600" rel="stylesheet">');
});
 
