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
        vue: "./libs/vue.min"
    },
    shim: {
        leafletGoogle: ["leaflet"],
        leafletClusterer: ["leaflet"]
  }
});


requirejs([
    "jquery", "vue", "lodash",
    "leaflet", "leafletGoogle", "leafletClusterer",
    "utils",
    "components/survey.component"
    
], function($, Vue, _, L, LG, LC, Utils, SurveyComponent) {
    "use strict";
    
    var componentMap = {
        "SurveyMapComponent": SurveyComponent
    };
    
    
    var state = {
        pins: {
            blue: Utils.generatePin(L, "blue"),
            green: Utils.generatePin(L, "green"),
            orange: Utils.generatePin(L, "orange"),
            purple: Utils.generatePin(L, "purple"),
            red: Utils.generatePin(L, "red")
        },
        map: null,
        methods: {
            addControl: addMapControl,
            removeControl: removeMapControl,
            addAction: addMapAction,
            removeAction: removeMapAction,
            showDetail: showMapDetail,
            selectPosition: selectMapPosition,
            toggleOverlay: toggleMapOverlay
        }
    };
    
    function addMapControl(id, html) {
        // ...
    }
    
    function removeMapControl(id) {
        // ...
    }
    
    function showMapDetail(title, html, onClose) {
        
        var detail = $("#map-detail");
        
        detail.find(".title .text").text(title);
        detail.find(".inner").html(html);
        detail.toggleClass("active", true);
        
        detail.off("click");
        detail.on("click", function(e) {
            
            if (onClose && onClose(e) === false) {
                return;
            }
            
            detail.find(".title .text").html("");
            detail.find(".inner").html("");
            detail.toggleClass("active", false);
        });
        
        return false;
    }
    
    function addMapAction(id, options) {
        
        options.id = id;
        options.title = options.title || "Action";
        options.icon = options.icon || "fa-info-circle";
        
        $("#map-actions").append(Utils.tpl.mapAction(options));
        
        if (options.callback) {
            $("#map-actions #"+id).on("click", options.callback);
        }
    }
    
    function removeMapAction(id) {
        $("#"+id).remove();
    }
    
    function selectMapPosition(callback) {
        
        toggleMapOverlay(true, "selecting");
        
        function finish(position) {
            state.map.off("click");
            toggleMapOverlay(false);
            callback(position);
            removeMapAction("SelectPosCancel");
        }
        
        
        state.map.on("click", function(e) {
            finish([e.latlng.lat, e.latlng.lng]);
        });
        
        
        addMapAction("SelectPosCancel", {
            title: "Cancel",
            colour: "red",
            icon: "fa-ban",
            callback: function(e) {
                finish(null);
            }
        });
    }
    
    function toggleMapOverlay(toggle, className) {
        
        if (!toggle) { $("#map-overlay").removeClass(); }
        else { $("#map-overlay").attr("class", "active " + className); }
    }
    
    
    
    
    // Setup the map ...
    function setupMap(config) {
        
        console.log("config", config);
        
        _.each(config.components, function(comp) {
            if (componentMap[comp.type]) {
                componentMap[comp.type](config.page, comp, state);
            }
        });
        
        
        
        state.map = L.map("map").setView([config.page.startLat, config.page.startLng], config.page.startZoom);
        
        state.map.zoomControl.setPosition("bottomright");
        
        
        if (config.page.tileset === "Google") {
            
            L.gridLayer.googleMutant({type: "roadmap"})
                .addTo(state.map);
        }
        else {
            
            var url = "http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png";
            
            var osm = new L.TileLayer(url, {
                attribution: "Map data © <a href=\"http://openstreetmap.org\">OpenStreetMap</a> contributors"
            });
            
            state.map.addLayer(osm);
        }
        
    }
    
    
    
    
    // Load the component config and set up components accordingly
    $.ajax(Utils.getUrl() + "mapConfig").then(setupMap);
    
    
    
    // Add a handler to resize the map height to fit the page
    $(window).resize(function() {
        $(".MapPage .main").height(
            $(window).height() - $("header nav").outerHeight() - $("footer").outerHeight()
        );
    }).resize();
    
    // Add a click listener to the overlay to propergate events to the #map
    $("#map-overlay").on("click", function(e) {
        $("#map").trigger(e);
    });
    
});
