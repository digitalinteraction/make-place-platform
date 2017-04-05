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
            
            $.ajax(Utils.apiUrl("/s/" + response.surveyId + "/r/" + response.id))
            .then(function(data) {
                
                var title = data.member.name || "Unknown";
                
                console.log(data);
                
                var html = Utils.tpl.surveyResponse(data);
                
                state.methods.showDetail(title, html, null);
                
            })
            .catch(function(error) {
                console.log(error);
            });
            
        });
        
        return marker;
    }
    
    function addPinButton(state) {
        
        state.methods.addAction(componentId, {
            title: "Add Response",
            colour: "green",
            callback: function(e) {
                
                state.methods.removeAction(componentId);
                
                state.methods.selectPosition(function(position) {
                    
                    addPinButton(state);
                    
                    if (position) {
                        console.log(position);
                    }
                });
            }
        });
    }
    
    
    
    
    return function(page, component, state) {
        
        componentId = "SurveyComponent-" + component.surveyID;
        
        // Add the "Add Response" button
        addPinButton(state);
        
        
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
