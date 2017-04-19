define(["jquery", "vue", "lodash", "utils"], function($, Vue, _, Utils) {
    "use strict";
    
    
    /** Constructor */
    var SurveyComponent = function(page, component, mapState) {
        
        // Setup variables
        this.addingMarker = null;
        
        
        // Setup state
        this.id = "survey-component-" + component.surveyID;
        this.page = page;
        this.component = component;
        this.state = mapState;
        
        
        // Create a clustering layer
        this.clusterer = L.markerClusterGroup();
        
        
        // Add the "Add Response" button
        if (this.component.canSubmit) {
            this.addPinButton();
        }
        
        
        // If we have view access
        if (this.component.canView) {
            
            // Add a dummy control
            // this.state.methods.addControl(this.componentId, "<p> Hello, World </p>");
            // $("#map-controls #"+this.componentId).on("click", function(e) {
            //     console.log(e);
            // });
            
            
            // Load responses
            var self = this;
            $.ajax(Utils.getOrigin() + "/s/" + self.component.surveyID + "/responses")
            .then(function(responses) {
                
                // Generate markers and add them to the layer
                _.each(responses, function(response) {
                    self.clusterer.addLayer(self.createResponseMarker(response, self.state.pins.blue));
                });
                
                self.state.map.addLayer(self.clusterer);
            });
        }
    };
    
    
    /* Class Methods */
    SurveyComponent.prototype.createResponseMarker = function(response, type) {
        
        // Get the key of where the location will be
        var pointKey = this.component.geoPointQuestion;
        var data = response.values[pointKey].value;
        
        
        // Create a marker with the response's position
        var marker = L.marker([data.geom.x, data.geom.y], { icon: type });
        marker.response = response;
        
        
        // Listen for clicks on the pin
        var self = this;
        marker.on("click", function(e) {
            
            // Pan to the pin's position
            self.state.map.panTo(e.latlng);
            
            
            // Fetch the pin's data over ajax
            $.ajax(Utils.apiUrl("/s/" + response.surveyId + "/r/" + response.id))
            .then(function(data) {
                
                // Show a detail with the ajax response
                self.state.methods.showDetail(data.title, data.body);
            })
            .catch(function(error) { console.log(error); });
        });
        
        
        // Return the new marker
        return marker;
    };
    
    SurveyComponent.prototype.addPinButton = function() {
        
        // Add an action via the state
        var self = this;
        this.state.methods.addAction(this.id, {
            title: "Add Response",
            colour: "green",
            icon: 'fa-plus',
            callback: function(e) {
                
                // On click, prompt for a position to be clicked
                // + rebind self for function call
                self.state.methods.selectPosition(self.positionPicked.bind(self));
            }
        });
    };
    
    SurveyComponent.prototype.positionPicked = function(position) {
        
        // If no position was selected, do nothing
        if (!position) { return; }
        
        
        // Add a marker to show where the new response will be
        this.addingMarker = L.marker(position, { icon: this.state.pins.orange });
        this.addingMarker.addTo(this.state.map);
        
        
        // Request the survey form over ajax
        var self = this;
        $.ajax(Utils.apiUrl("/s/"+this.component.surveyID+"/view"))
        .then(function(data) {
            
            // Show a detail with the form
            self.state.methods.showDetail(data.title, data.content, self.removeSurveyForm.bind(self));
            
            // Get the name of the position field from our config
            var posField = 'Fields['+self.component.geoPointQuestion+']';
            
            // Add the hidden latlng fields to the form
            var extras = '';
            extras += '<input type="hidden" name="'+posField+'[x]" value="'+position[0]+'">';
            extras += '<input type="hidden" name="'+posField+'[y]" value="'+position[1]+'">';
            $('#map-detail .inner form').append(extras);
            
            
            // Listen for the form submitting
            // + rebind this for function call
            $('#map-detail .inner form').on('submit', self.submitSurvey.bind(self));
        });
    };
    
    SurveyComponent.prototype.submitSurvey = function(e) {
        
        // Stop normal form submission
        e.preventDefault();
        
        
        // TODO: Disable submission while processing ...
        
        
        // Submit the form over ajax
        var self = this;
        $.post(e.target.action, $(e.target).serialize())
        .then(function(data) {
            
            // Add a pin from the response
            self.clusterer.addLayer(self.createResponseMarker(data, self.state.pins.blue));
            
            // Remove the survey form
            self.removeSurveyForm();
        })
        .catch(function(error) {
            
            console.log(error);
            
            // TODO: Re-enable submission
            
            // TODO: Present error messages
            
            // ...
        });
    };
    
    SurveyComponent.prototype.removeSurveyForm = function() {
        
        // Remove the adding marker if we have one
        if (this.addingMarker) {
            this.addingMarker.remove();
            this.addingMarker = null;
        }
        
        
        // Remove the detail view via the state
        this.state.methods.hideDetail();
    };
    
    
    
    // Return our class
    return SurveyComponent;
});
