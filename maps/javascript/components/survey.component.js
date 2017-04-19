define(["jquery", "vue", "lodash", "utils"], function($, Vue, _, Utils) {
    "use strict";
    
    var componentId = null;
    var config = null;
    var addingMarker = null;
    var clusterer = null;
    var state = null;
    
    function createResponseMarker(response, type) {
        
        var pointKey = config.component.geoPointQuestion;
        var data = response.values[pointKey].value;
        
        var marker = L.marker([data.geom.x, data.geom.y], {
            icon: type
        });
        
        
        marker.response = response;
        
        
        marker.on("click", function(e) {
            
            var response = this.response;
            state.map.panTo(e.latlng);
            $.ajax(Utils.apiUrl("/s/" + response.surveyId + "/r/" + response.id))
            .then(function(data) {
                
                state.methods.showDetail(data.title, data.body);
            })
            .catch(function(error) { console.log(error); });
        });
        
        return marker;
    }
    
    function addPinButton() {
        
        state.methods.addAction(componentId, {
            title: "Add Response",
            colour: "green",
            icon: 'fa-plus',
            callback: function(e) {
                
                state.methods.selectPosition(positionPicked);
            }
        });
    }
    
    function positionPicked(position) {
        
        if (!position) { return; }
        
        var id = config.component.surveyID;
        
        addingMarker = L.marker(position, {
            icon: state.pins.orange
        });
        
        
        addingMarker.addTo(state.map);
        
        
        $.ajax(Utils.apiUrl("/s/"+id+"/view"))
        .then(function(data) {
            
            state.methods.showDetail(data.title, data.content, removeSurveyForm);
            
            var posField = 'Fields['+config.component.geoPointQuestion+']';
            
            // Add the latlng to the form
            var extras = '';
            extras += '<input type="hidden" name="'+posField+'[x]" value="'+position[0]+'">';
            extras += '<input type="hidden" name="'+posField+'[y]" value="'+position[1]+'">';
            
            
            $('#map-detail .inner form').append(extras);
            
            $('#map-detail .inner form').on('submit', submitSurvey);
        });
    }
    
    function submitSurvey(e) {
        
        e.preventDefault();
        
        var url = e.target.action;
        
        // TODO: Disable submission while processing ...
        
        $.post(e.target.action, $(e.target).serialize())
        .then(function(data) {
            
            clusterer.addLayer(createResponseMarker(data, state.pins.blue));
            
            removeSurveyForm();
            
            state.methods.hideDetail();
        })
        .catch(function(error) {
            
            console.log(error);
            
            // TODO: Re-enable submission
            
            // TODO: Present error messages
            
            // ...
        });
    }
    
    function removeSurveyForm() {
        
        console.log('Remove survey');
        if (addingMarker) {
            addingMarker.remove();
        }
    }
    
    
    
    /* The function to setup the component */
    return function(page, component, mapState) {
        
        // Setup state
        componentId = "survey-component-" + component.surveyID;
        config = {
            page: page,
            component: component
        };
        state = mapState;
        
        
        // Create a clustering layer
        clusterer = L.markerClusterGroup();
        
        
        // Add the "Add Response" button
        addPinButton();
        
        
        // Add a dummy control
        state.methods.addControl(componentId, "<p> Hello, World </p>");
        $("#map-controls #"+componentId).on("click", function(e) {
            console.log(e);
        });
        
        
        // Load responses
        $.ajax({
            url: Utils.getOrigin() + "/s/" + component.surveyID + "/responses?onlygeo",
            success: function(responses) {
                
                // Generate markers and add them to the layer
                _.each(responses, function(response) {
                    clusterer.addLayer(createResponseMarker(response, state.pins.blue));
                });
                
                state.map.addLayer(clusterer);
                
            }
        });
        
        // ...
    };
});
