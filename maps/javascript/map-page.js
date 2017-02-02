/* jshint esversion: 6*/


// The map callback, assigned to later (in scope)
var setupMap;


(function($, Vue) {
    'use strict';
    
    // ...
    
    const componentSetup = {
        SurveyMapComponent: function(page, comp) {
            
            // console.log('Survey Comp Setup!');
            
            var surveyID = comp.surveyID;
            
            console.log(surveyID);
            
            // Fetch responses
            
            // $.ajax({ ... });
            
            
        }
    };
    
    
    function setupComponents() {
        
        var url = window.location.href;
        
        if (url.substr(-1) != '/') url += '/';
        
        $.ajax({
            url: url + 'mapConfig',
            success: function(data) {
                
                for (var i in data.components) {
                    
                    var comp = data.components[i];
                    componentSetup[comp.type](data.page, comp);
                }
                
            },
            error: function(error) {
                
                console.log(error);
            }
        });
    }
    
    
    
    
    // Resize our map when the page resizes
    // -> Also sets the initial size
    $(document).ready(function() {
        $(window).resize(function() {
            $(".MapPage .main").height(
                $(window).height() - $("header").outerHeight() - $("footer").outerHeight()
            );
        }).resize();
    });
    
    
    
    var myMap = document.getElementById('map-app');
    
    
    var map = new Vue({
        el: '#map-app',
        data: myMap.dataset
    });
    
    
    
    setupMap = function() {
        
        var myMap = document.getElementById('map-app');
        
        // console.log(myMap.dataset);
        
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: parseFloat(myMap.dataset.centerZoom),
            center: {
                lat: parseFloat(myMap.dataset.centerLat),
                lng: parseFloat(myMap.dataset.centerLng)
            }
        });
        
        
        setupComponents();
    };
    
    
})($, Vue);
