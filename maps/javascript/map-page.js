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
        
        setupComponents();
    };
    
    
    // Variables
    var _map = null;
    
    
    // Constants
    const resourceBase = 'maps/images/';
    const clusterBase = resourceBase + 'cluster/m';
    const clusterStyles = [
        { textColor: 'white', textSize: 24, url: clusterBase+'1.png', height: 72, width: 72, },
        { textColor: 'white', textSize: 20, url: clusterBase+'2.png', height: 84, width: 84 },
        { textColor: 'white', textSize: 18, url: clusterBase+'3.png', height: 96, width: 96 },
        { textColor: 'white', textSize: 16, url: clusterBase+'4.png', height: 108, width: 108 },
        { textColor: 'white', textSize: 14, url: clusterBase+'5.png', height: 132, width: 132 }
    ];
    
    // Setup for each component
    // IDEA: refactor to seperate files? ~ requirejs?
    const componentSetup = {
        SurveyMapComponent: function(page, comp) {
            
            // Component's state
            var isAddingPin = false;
            var markerCluster = null;
            
            
            function toggleAddButton(value) {
                
                // Get the button
                var button = $('.pin-button');
                
                // Toggle the button's state
                button.toggleClass('red', value);
                button.toggleClass('green', !value);
                button.find('.text').html(value ? 'Cancel' : 'Add Pin');
                
                // Update the property
                isAddingPin = value;
            }
            
            function onSubmitSurvey(e) {
                
                // Stop the normal form submission
                e.preventDefault();
                
                // Serialize the form's values
                var url = this.action;
                
                // Post the form
                $.post(this.action, $(this).serialize())
                    .then(function(data) {
                        
                        // Add a pin with the data
                        var marker = addResponseMarker(data);
                        markerCluster.addMarkers([marker]);
                        
                        // Remove the popover
                        removePopover();
                        
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            }
            
            function addResponseMarker(response) {
                
                // Create a marker from its position
                var marker = new google.maps.Marker({
                    position: { lat: response.lat, lng: response.lng },
                    icon: resourceBase + 'pin.png'
                });
                
                // Store the response on the marker
                marker.response = response;
                
                // Add a click handler to the marker
                marker.addListener('click', pinClickHandler);
                
                // Return the marker into our map
                return marker;
            }
            
            
            // Fetch responses & render them
            $.ajax({
                url: `${window.location.origin}/s/${comp.surveyID}/responses?onlygeo`,
                success: function(data) {
                    
                    // Map each response to a map marker
                    var markers = data.map(addResponseMarker);
                    
                    
                    // Create a clusterer to cluster nearby markers
                    markerCluster = new MarkerClusterer(_map, markers, {
                        averageCenter: true,
                        styles: clusterStyles
                    });
                },
                error: function(error) {
                    console.log(error);
                }
            });
            
            
            
            // Add click handler to the add button
            $('.action.pin-button').on('click', function(e) {
                
                // Simply toggle the buttons state
                toggleAddButton(!isAddingPin);
            });
            
            
            // Add a click listener to the map
            _map.addListener('click', function(e) {
                
                // If not in 'add' mode, dont do anything
                if (!isAddingPin) { return; }
                
                // Get the lat/lng of where was clicked
                var pos = {
                    lat: e.latLng.lat(),
                    lng: e.latLng.lng()
                };
                
                $.ajax(`${window.location.origin}/s/${comp.surveyID}/view?${$.param(pos)}`)
                    .then(function(data) {
                        
                        addPopover(data.title, data.content, 'survey-form');
                        
                        $('.survey-form form').on('submit', onSubmitSurvey);
                        
                        toggleAddButton(false);
                    });
                
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
        
        var base = window.location.origin;
        
        $.ajax(`${base}/s/${response.surveyId}/r/${response.id}/view`)
            .then(function(data) {
                addPopover(data.title, data.content, 'survey-response');
            })
            .catch(function(error) {
                
                console.log(error);
            });
        
    }
    
    function addPopover(title, content, classes = '') {
        
        removePopover();
        
        
        var popover = `
            <div class="popover-bg"></div>
            <div class="popover-inner ${classes}">
                <h2 class="title">
                    ${title}
                    <i class="close-button fa fa-times" aria-hidden="true"></i>
                </h2>
                <div class="content"> ${content} </div>
            </div>`;
        
        $('.popover-container').html(popover);
        
        $('.popover-inner .close-button').on('click', function() {
            removePopover();
        });
    }
    
    function removePopover() {
        
        
        
        // Remove previous popovers
        $('.popover-container').html('');
        
        // Unregister listeners
        // $('.popover-inner .close-button')
        // ?
    }
    
    
    
    
    // Resize our map when the page resizes
    // -> Also sets the initial size
    $(document).ready(function() {
        $(window).resize(function() {
            $(".MapPage .main").height(
                $(window).height() - $("header nav").outerHeight() - $("footer").outerHeight()
            );
        }).resize();
    });
    
    
})($, Vue);
