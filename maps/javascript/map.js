requirejs.config({
    paths: {
        jquery: "./libs/jquery-3.1.1.min",
        leaflet: './libs/leaflet.min',
        leafletGoogle: './libs/leaflet-google',
        leafletClusterer: './libs/leaflet-markercluster.min',
        lodash: "./libs/lodash.min",
        utils: "./libs/utils",
        vue: "./libs/vue.min"
    }
});


requirejs([
    "jquery", "vue", "lodash",
    "leaflet", "leafletGoogle", "leafletClusterer",
    "utils",
    "components/survey.component"
    
], function($, Vue, _, L, LG, LC, Utils, SurveyComponent) {
    "use strict";
    
    console.log(L.markerClusterGroup);
    
    var componentMap = {
        "SurveyMapComponent": SurveyComponent
    };
    
    
    var state = {
        pins: {
            blue: Utils.generatePin(L, 'blue'),
            green: Utils.generatePin(L, 'green'),
            orange: Utils.generatePin(L, 'orange'),
            purple: Utils.generatePin(L, 'purple'),
            red: Utils.generatePin(L, 'red')
        },
        map: null,
        methods: {
            addControl: function(id, html) { },
            removeControl: function(id) { },
            addAction: function(id, options) { },
            removeAction: function(id) { },
            showPopup: function(html, onClose) { return false; },
            selectPosition: function(callback) { return [0, 0]; }
        }
    };
    
    
    
    // Setup the map ...
    function setupMap(config) {
        
        console.log("config", config);
        
        _.each(config.components, function(comp) {
            if (componentMap[comp.type]) {
                componentMap[comp.type](config.page, comp, state);
            }
        });
        
        state.map = L.map('map').setView([config.page.startLat, config.page.startLng], config.page.startZoom);
        
        
        if (config.page.tileset === "Google") {
            
            L.gridLayer.googleMutant({type: 'roadmap'})
                .addTo(state.map);
        }
        else {
            
            var url = "http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png";
            
            var osm = new L.TileLayer(url, {
                attribution: "Map data © <a href='http://openstreetmap.org'>OpenStreetMap</a> contributors"
            });
            
            state.map.addLayer(osm);
        }
        
    }
    
    
    
    
    // Load the component config and set up components accordingly
    $.ajax(Utils.getUrl() + 'mapConfig').then(setupMap);
    
    
    
    // Add a handler to resize the map height to fit the page
    $(window).resize(function() {
        $(".MapPage .main").height(
            $(window).height() - $("header nav").outerHeight() - $("footer").outerHeight()
        );
    }).resize();
    
});
