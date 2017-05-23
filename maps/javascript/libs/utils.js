define([
    "jquery", "vue", "handlebars"
], function($, Vue, Handlebars) {
    "use strict";
    
    return {
        
        getUrl: function() {
            var url = window.location.href;
            if (url.substr(-1) != "/") url += "/";
            return url;
        },
        
        getOrigin: function() {
            return window.location.origin;
        },
        
        apiUrl: function(endpoint) {
            return window.location.origin + endpoint;
        },
        
        generatePin: function(L, colour) {
            return L.icon({
                iconUrl: "/maps/images/pins/pin-" + colour + ".png",
                iconSize: [30, 56],
                iconAnchor: [15, 40]
            });
        },
    };
});
