define(["jquery", "vue", "lodash", "utils"], function($, Vue, _, Utils) {
    "use strict";
    
    var componentId;
    
    function createResponseMarker(response, type, state) {
        
        var marker = L.marker([response.lat, response.lng], {
            icon: type
        });
        
        marker.response = response;
        
        
        marker.on("click", function(e) {
            
            var response = this.response;
            state.map.panTo(e.latlng);
            $.ajax(Utils.apiUrl("/s/" + response.surveyId + "/r/" + response.id))
            .then(function(data) {
                
                var title = data.member.name || "Unknown";
                state.methods.showDetail(title, Utils.tpl.surveyResponse(data), null);
            })
            .catch(function(error) { console.log(error); });
        });
        
        return marker;
    }
    
    function addPinButton(state) {
        
        state.methods.addAction(componentId, {
            title: "Add Response",
            colour: "green",
            icon: 'fa-plus',
            callback: function(e) {
                
                state.methods.selectPosition(function(position) {
                    
                    if (position) { console.log(position); }
                });
            }
        });
    }
    
    
    
    /* The function to setup the component */
    return function(page, component, state) {
        
        componentId = "survey-component-" + component.surveyID;
        
        
        // Add the "Add Response" button
        addPinButton(state);
        
        
        // Add a dummy control
        state.methods.addControl(componentId, "<p> Hello, World </p>");
        $("#map-controls #"+componentId).on("click", function(e) {
            console.log(e);
        });
        
        
        // Load responses
        $.ajax({
            url: Utils.getOrigin() + "/s/" + component.surveyID + "/responses?onlygeo",
            success: function(responses) {
                
                // Create a clustering layer
                var cluster = L.markerClusterGroup();
                
                // Generate markers and add them to the layer
                _.each(responses, function(response) {
                    cluster.addLayer(createResponseMarker(response, state.pins.blue, state));
                });
                
                // Add the layer to the map
                state.map.addLayer(cluster);
            }
        });
        
        // ...
    };
});
