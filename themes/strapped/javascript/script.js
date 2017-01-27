$(document).ready(function() {
    
    // Offset the bottom of the body by the footer because its positioned absolutely
    $(window).resize(function() {
        $("html body").css("padding-bottom", $("footer").outerHeight(true) );
        $("html body").css("padding-top", $("header").outerHeight(true) );
    }).resize();
    
    // Add google fonts asnychronoulsy so they don't slow down page load times
    $('head').append(
        '<link href="https://fonts.googleapis.com/css?family=Overpass" rel="stylesheet">'
    );
});
 
