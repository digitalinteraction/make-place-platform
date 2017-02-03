/* jshint esversion: 6*/


// The map callback, assigned to later (in scope)
var setupMap = null;


(function($, Vue) {
    
    // Entrypoint
    setupMap = function() {
        
        var myMap = document.getElementById('map-app');
        
        _map = new google.maps.Map(document.getElementById('map'), {
            zoom: parseFloat(myMap.dataset.centerZoom),
            center: {
                lat: parseFloat(myMap.dataset.centerLat),
                lng: parseFloat(myMap.dataset.centerLng)
            }
        });
        
        _infoWindow = new google.maps.InfoWindow({map: _map});
        _infoWindow.close();
        
        setupComponents();
    };
    
    
    // Properties
    var _map = null;
    var _infoWindow = null;
    
    
    
    
    const componentSetup = {
        SurveyMapComponent: function(page, comp, map) {
            
            // Get the survey id
            var surveyID = comp.surveyID;
            
            
            // Fetch responses
            var base = window.location.origin;
            $.ajax({
                url: base + '/s/' + surveyID + '/responses?onlygeo',
                success: function(data) {
                    
                    var textColour = 'white';
                    var resBase = 'maps/images/';
                    var imgBase = resBase + 'cluster/m';
                    
                    var icons = {
                        response: {
                            name: 'Response',
                            icon: resBase + 'pin.png'
                        }
                    };
                    
                    var markers = data.map(function(response, i) {
                        var marker = new google.maps.Marker({
                            position: { lat: response.lat, lng: response.lng },
                            icon: icons.response.icon
                        });
                        
                        marker.response = response;
                        marker.addListener('click', pinClickHandler);
                        
                        return marker;
                    });
                    
                    var markerCluster = new MarkerClusterer(_map, markers, {
                        averageCenter: true,
                        styles: [
                            { textColor: textColour, textSize: 24, url: imgBase + '1.png', height: 72, width: 72, },
                            { textColor: textColour, textSize: 20, url: imgBase + '2.png', height: 84, width: 84 },
                            { textColor: textColour, textSize: 18, url: imgBase + '3.png', height: 96, width: 96 },
                            { textColor: textColour, textSize: 16, url: imgBase + '4.png', height: 108, width: 108 },
                            { textColor: textColour, textSize: 14, url: imgBase + '5.png', height: 132, width: 132 }
                        ]
                    });
                
                    
                },
                error: function(error) {
                    
                    console.log(error);
                }
            });
            
            
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
    
    function pinClickHandler(marker) {
        
        var response = this.response;
        
        var pos = {
            lat: marker.latLng.lat(),
            lng: marker.latLng.lng()
        };
        
        _infoWindow.setPosition(pos);
        _infoWindow.setContent('Response, id: ' + response.id);
        
        _infoWindow.open(_map);
        
        // _map.setCenter(pos);
        
        // console.log(marker.latLng);
        // console.log('display: '+ response.id);
    }
    
    function addPopover() {
        
        // ...
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
    
    
})($, Vue);
