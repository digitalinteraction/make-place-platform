/* jshint esversion: 6*/


// The map callback, assigned to later (in scope)
var setupMap;


(function(Vue) {
    'use strict';
    
    // ...
    
    var myMap = document.getElementById('map-app');
    
    
    var map = new Vue({
        el: '#map-app',
        data: myMap.dataset
    });
    
    
    
    setupMap = function() {
        
        var myMap = document.getElementById('map-app');
        
        console.log(myMap.dataset);
        
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: parseFloat(myMap.dataset.centerZoom),
            center: {
                lat: parseFloat(myMap.dataset.centerLat),
                lng: parseFloat(myMap.dataset.centerLng)
            }
        });
    };
    
    
})(Vue);
