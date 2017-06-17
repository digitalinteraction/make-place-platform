/* eslint-disable */

requirejs.config({
    paths: {
        handlebars: "./libs/handlebars-v4.0.5",
        jquery: "./libs/jquery-3.1.1.min",
        leaflet: "./libs/leaflet.min",
        leafletGoogle: "./libs/leaflet-google",
        leafletClusterer: "./libs/leaflet-markercluster.min",
        lodash: "./libs/lodash.min",
        text: "./libs/text",
        utils: "./libs/utils",
        vue: "./libs/vue.min",
        vuex: "./libs/vuex"
    },
    shim: {
        leafletGoogle: ["leaflet"],
        leafletClusterer: ["leaflet"]
    }
});


requirejs([
    "jquery", "vue", "vuex", "lodash",
    "leaflet", "leafletGoogle", "leafletClusterer",
    "utils",
    "map-components/survey.component",
    "vue-components/DefaultMapComponent",
    "vue-components/PickingMapComponent",
    "vue-components/DetailMapComponent",
    
], function($, Vue, VueX, _, L, LG, LC, Utils,
    SurveyComponent, DefaultMapComp, PickingMapComp, DetailMapComp
) {
    "use strict";
    
    var componentMap = {
        "SurveyMapComponent": SurveyComponent
    };
    var detailOnClose = null;
    
    
    
    /*
    var app = new Vue({
        el: "#map-app",
        data: {
            overlayMessage: null,
            controls: [ ],
            actions: [ ],
            detail: null,
            showActions: true,
            mobileActions: false,
            mobileControls: false,
            showMobileOptions: true,
            cancelAction: null,
            mapAction: null,
            isSelecting: false,
            
            isMobile: false,
            mobileActionsToggled: false
        },
        mounted: function() {
            this.onResize();
            window.addEventListener('resize', this.onResize);
        },
        computed: {
            mobileOptionsEnabled: function() {
                return this.isMobile && this.showActions && this.showMobileOptions;
            },
            actionsEnabled: function() {
                return this.isMobile ? this.mobileActions : this.showActions;
            },
            controlsEnabled: function() {
                return this.isMobile ? this.mobileControls : this.controls.length > 0;
            },
            
            
            showMainActions: function() {
                
                // No actions when showing a detail or overlay
                if (this.detail || this.overlayMessage) { return false; }
                
                // Only show mobile actions when toggled
                if (this.isMobile && !this.mobileActionsToggled) { return false; }
                
                // Don't show actions if there aren't any!
                return this.actions.length > 0;
            }
        },
        methods: {
            cancel: function(e) {
                if (this.cancelAction) this.cancelAction(e);
                this.cancelAction = null;
            },
            closeDetail: function(e) {
                this.detail = null;
                this.showActions = true;
                if (detailOnClose) detailOnClose();
                detailOnClose = null;
            },
            minifyDetail: function(e) {
                if (!this.detail) return;
                this.detail.minimized = !this.detail.minimized;
            },
            mapClicked: function(e) {
                if (this.mapAction) this.mapAction(e);
                this.mapAction = null;
            },
            addMobileActions: function(e) {
                
                this.detail = null;
                this.overlayMessage = "";
                this.showMobileOptions = false;
                this.mobileActions = true;
                this.cancelAction = function() {
                    this.overlayMessage = null;
                    this.showMobileOptions = true;
                    this.mobileActions = false;
                };
            },
            addMobileControls: function(e) {
                this.detail = null;
                this.overlayMessage = "";
                this.showMobileOptions = false;
                this.mobileControls = true;
                this.cancelAction = function() {
                    this.overlayMessage = null;
                    this.showMobileOptions = true;
                    this.mobileControls = false;
                };
            },
            
            
            toggleMobileActions: function() {
                console.log('here');
                this.mobileActionsToggled = !this.mobileActionsToggled;
            },
            onResize: function() {
                this.isMobile = window.outerWidth < 767;
            }
        }
    });
    */
    
    
    
    
    var defaultComp = new DefaultMapComp();
    var pickingComp = new PickingMapComp();
    var detailComp = new DetailMapComp();
    
    Vue.use(VueX);
    
    
    var testActions = [
        { title: 'Map Info', colour: 'orange' },
        { title: 'I saw a pug!', icon: 'info', onClick: function(e) {
        console.log('Ouch', e);
        } }
    ];
    
    
    var store = new VueX.Store({
        state: {
            actions: testActions
        }
    });
    
    
    
    
    
    var app = new Vue({
        el: "#map-app",
        store: store,
        data: {
            currentState: defaultComp,
            isMobile: false
        },
        mounted: function() {
            this.onResize(); window.addEventListener('resize', this.onResize);
        },
        methods: {
            onResize: function() { this.isMobile = window.outerWidth < 767; }
        }
    });
   
    
    var state = {
        pins: {
            blue: Utils.generatePin(L, "blue"),
            green: Utils.generatePin(L, "green"),
            orange: Utils.generatePin(L, "orange"),
            purple: Utils.generatePin(L, "purple"),
            red: Utils.generatePin(L, "red")
        },
        map: null,
        app: app,
        methods: {
            toggleElem: toggleElem,
            addControl: addMapControl,
            removeControl: removeMapControl,
            addAction: addMapAction,
            removeAction: removeMapAction,
            showDetail: showMapDetail,
            hideDetail: hideMapDetail,
            selectPosition: selectMapPosition,
            toggleOverlay: toggleMapOverlay
        },
        components: {}
    };
    
    
    
    
    function toggleElem(selector, toggle) {
        $(selector).toggleClass("active", toggle);
    }
    
    
    
    
    
    function addMapControl(id, contents) {
        
        // Remove previous instances of the control
        app.$data.controls.push({
            id: id,
            contents: contents
        });
    }
    
    function removeMapControl(id) {
        app.$data.controls = _.filter(app.$data.controls, function(control) {
            return control.id !== id;
        });
    }
    
    function showMapDetail(title, component, onClose) {
        
        // Close the old detail, if there was one
        hideMapDetail();
        
        
        // Get the detail object
        app.$data.detail = {
            title: title,
            component: component,
            minimized: false
        };
        
        
        // Set it up with the passed values
        app.$data.showActions = false;
        
        
        // Store the callback
        detailOnClose = onClose;
    }
    
    function hideMapDetail() {
        
        if (detailOnClose) detailOnClose();
        detailOnClose = null;
        
        app.$data.showActions = true;
        app.$data.detail = null;
    }
    
    function addMapAction(id, options) {
        
        app.$data.actions.push({
            id: id,
            title: options.title || "Action",
            icon: options.icon || "fa-info-circle",
            colour: options.colour || "blue",
            onClick: function(e) {
                actionChosen();
                if (options.callback) options.callback(e);
            }
        });
    }
    
    function removeMapAction(id) {
        app.$data.actions = _.filter(app.$data.actions, function(action) {
            return action.id !== id;
        });
    }
    
    function selectMapPosition(callback) {
        
        app.$data.overlayMessage = "Pick a place on the map";
        app.$data.isSelecting = true;
        
        app.$data.showActions = false;
        
        function finish(position) {
            
            app.$data.overlayMessage = null;
            app.$data.isSelecting = false;
            
            app.$data.showActions = true;
            app.$data.cancelAction = null;
            app.$data.mapAction = null;
            
            callback(position);
        }
        
        app.$data.mapAction = function(e) {
            var pos = state.map.mouseEventToLatLng(e);
            finish([pos.lat, pos.lng]);
        };
        
        app.$data.cancelAction = function(e) {
            finish(null);
        };
    }
    
    function toggleMapOverlay(toggle, className, message) {
        
        app.$data.overlayMessage = message || null;
    }
    
    
    
    
    
    
    
    
    
    
    // Setup the map ...
    function setupMap(config) {
        
        // Print the config for debug
        // console.log("config", config);
        
        
        // Configure the Leaflet map
        var mapConfig = { attributionControl: config.page.tileset !== "Google" };
        state.map = L.map("map", mapConfig).setView([config.page.startLat, config.page.startLng], config.page.startZoom);
        state.map.zoomControl.setPosition("bottomright");
        state.clusterer = L.markerClusterGroup();
        
        
        // Load each component
        // _.each(config.components, function(comp) {
        //     if (componentMap[comp.type]) {
        //
        //         // Get the type of the component to create
        //         var ComponentType = componentMap[comp.type];
        //
        //         // Create and store the component
        //         var component = new ComponentType(config.page, comp, state);
        //         componentMap[component.id] = component;
        //     }
        // });
        
        
        
        
        
        // Add a tileset based on the config
        if (config.page.tileset === "Google") {
            
            // If google, add google tiles
            L.gridLayer.googleMutant({type: "roadmap"}).addTo(state.map);
        }
        else {
            
            // Otherwise, use OpenStreetMap tiles
            var url = "http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png";
            
            // Add the tiles as a layer
            var osm = new L.TileLayer(url, {
                attribution: "Map data © <a href=\"http://openstreetmap.org\">OpenStreetMap</a> contributors"
            });
            
            state.map.addLayer(osm);
        }
        
        state.map.addLayer(state.clusterer);
    }
    
    
    
    
    // Load the component config and set up components accordingly
    $.ajax(Utils.getUrl() + "mapConfig").then(setupMap);
    
    
    
    // Add a handler to resize the map height to fit the page
    $(window).resize(function() {
        
        var footerHeight = $("footer").is(":visible") ? $("footer").outerHeight() : 0;
        
        $(".MapPage .main").height(
            $(window).height() - $("header nav").outerHeight() - footerHeight
        );
    }).resize();
    
    
    
    // Listen for the escape key
    $(document).on("keyup", function(e) {
        if (e.keyCode == 27) {
            $("#map-detail .close-button").trigger("click");
        }
    });
    
    function actionChosen() {
        
        // Remove overlay
        toggleMapOverlay(false);
        
        // Remove cancel button
        app.$data.cancelAction = null;
        
        // Remove the mobile actions
        app.$data.mobileActions = false;
    }
    
});
