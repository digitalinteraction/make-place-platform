define(["jquery", "vue", "lodash", "leaflet", "utils"], function($, Vue, _, L, Utils) {
    "use strict";
    
    
    /** Constructor */
    var SurveyComponent = function(page, component, mapState) {
        
        // Don't do anything if the positionQ isn't set
        if (!component.positionQuestion) { return; }
        
        // Setup variables
        this.addingMarker = null;
        this.highlightLayer = null;
        this.formFiles = {};
        
        
        // Setup state
        this.id = "survey-component-" + component.surveyID;
        this.page = page;
        this.component = component;
        this.state = mapState;
        
        
        // Add the "Add Response" button
        if (this.component.canSubmit) {
            this.addPinButton();
        }
        
        
        // If we have view access
        if (this.component.canView) {
            
            // Load responses
            var self = this;
            $.ajax(Utils.getOrigin() + "/survey/" + self.component.surveyID + "/responses")
            .then(function(responses) {
                
                // Generate markers and add them to the layer
                _.each(responses, function(response) {
                    self.state.clusterer.addLayer(self.createResponseMarker(response));
                });
            });
        }
    };
    
    
    /* Class Methods */
    SurveyComponent.prototype.createResponseMarker = function(response) {
        
        // Get the key of where the location will be
        var pointKey = this.component.positionQuestion;
        var data = response.values[pointKey].value;
        
        
        // Get the pin type from our config
        var type = this.state.pins[this.component.pinColour || "blue"];
        
        
        // Create a marker with the response's position
        var marker = L.marker([data.geom.x, data.geom.y], { icon: type });
        marker.response = response;
        
        
        // Listen for clicks on the pin
        marker.on("click", this.responseClicked.bind(this));
        
        
        // Return the new marker
        return marker;
    };
    
    SurveyComponent.prototype.responseClicked = function(e) {
        
        // Pan to the pin's position
        this.state.map.panTo(e.latlng);
        
        
        // Fetch the pin's data over ajax
        var response = e.target.response;
        var self = this;
        $.ajax(Utils.apiUrl("/survey/" + response.surveyId + "/response/" + response.id))
        .then(function(data) {
            
            // Show a detail with the ajax response
            self.state.methods.showDetail(data.title, data.body, function() {
                self.responseDetailRemoved();
            });
            
            // Highlight the pin
            self.highlightResponse(response);
        })
        .catch(function(error) { console.log(error); });
    };
    
    SurveyComponent.prototype.responseDetailRemoved = function() {
        
        if (this.highlightLayer) {
            this.highlightLayer.remove();
            this.highlightLayer = null;
        }
    };
    
    SurveyComponent.prototype.addPinButton = function() {
        
        // Add an action via the state
        var self = this;
        this.state.methods.addAction(this.id, {
            title: this.component.actionMessage || "Add Response",
            colour: this.component.actionColour || "green",
            icon: 'fa-plus',
            callback: function(e) {
                
                // On click, prompt for a position to be clicked
                // + rebind self for function call
                self.state.methods.selectPosition(self.positionPicked.bind(self));
            }
        });
    };
    
    SurveyComponent.prototype.highlightResponse = function(response) {
        
        if (this.component.highlightQuestion && response.values[this.component.highlightQuestion]) {
            var question = response.values[this.component.highlightQuestion];
            
            if (question.value && question.value.type == "LINESTRING") {
                
                var points = question.value.geom.map(function(value, key) {
                    return new L.LatLng(value.x, value.y);
                });
                
                this.highlightLayer = new L.Polyline(points, {
                    color: 'red',
                    weight: 10,
                    opacity: 0.5,
                    smoothFactor: 1
                });
                
                this.state.map.addLayer(this.highlightLayer);
            }
        }
    };
    
    SurveyComponent.prototype.positionPicked = function(position) {
        
        // If no position was selected, do nothing
        if (!position) { return; }
        
        
        // Add a marker to show where the new response will be
        this.addingMarker = L.marker(position, { icon: this.state.pins.orange });
        this.addingMarker.addTo(this.state.map);
        
        
        // Request the survey form over ajax
        var self = this;
        $.ajax(Utils.apiUrl("/survey/"+this.component.surveyID+"/view"))
        .then(function(data) {
            
            // Show a detail with the form
            self.state.methods.showDetail(data.title, data.content, self.removeSurveyForm.bind(self));
            
            // Get the name of the position field from our config
            var posField = 'fields['+self.component.positionQuestion+']';
            
            // Add the hidden latlng fields to the form
            var extras = '';
            extras += '<input type="hidden" name="'+posField+'[x]" value="'+position[0]+'">';
            extras += '<input type="hidden" name="'+posField+'[y]" value="'+position[1]+'">';
            
            
            // Wait for vue to render the detail
            setTimeout(function() {
                
                $('#map-detail .inner form').append(extras);
                
                
                // Add listener for file changes
                $('input[type=file]').on('change', self.fileAttatched.bind(self));
                
                
                // Listen for the form submitting
                // + rebind this for function call
                $('#map-detail .inner form').on('submit', self.submitSurvey.bind(self));
                
            }, 0);
        });
    };
    
    SurveyComponent.prototype.fileAttatched = function(e) {
        var name = $(e.target).attr('name');
        this.formFiles[name] = e.target.files;
    };
    
    SurveyComponent.prototype.submitSurvey = function(e) {
        
        // Stop normal form submission
        e.preventDefault();
        
        
        // TODO: Disable submission while processing ...
        
        // Process form files
        var formData = new FormData(e.target);
        
        
        // Submit the form over ajax
        var self = this;
        $.ajax({
            url: e.target.action,
            type: 'POST',
            contentType: false,
            data: formData,
            processData: false
        })
        .then(function(data) {
            
            // Add a pin from the response
            self.state.clusterer.addLayer(self.createResponseMarker(data));
            
            // Remove the survey form
            self.removeSurveyForm();
            
            // Remove the detail view via the state
            self.state.methods.hideDetail();
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
        
    };
    
    
    
    // Return our class
    return SurveyComponent;
});
