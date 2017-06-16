<% require themedCSS('survey') %>


<% require css('maps/css/maps.css') %>
<% require css('maps/css/leaflet.css') %>
<% require css('maps/css/marker-cluster.css') %>



<%-- <div id="map-app" v-cloak
  :class="{selecting: isSelecting}"
  :class="{'is-mobile': isMobile}"> --%>

<div id="map-app" v-cloak :class="{'is-mobile': isMobile}">
  
  
  
  <div class="map-state">
    <component :is="currentState" :is-mobile="isMobile"></component>
  </div>
  
  
  
  
  
  <%-- The actions available on the map --%>
  <%-- <p v-if="showMainActions" class="action-list">
    <span v-for="a in actions">
      <span @click.prevent="a.onClick"
        class="action with-icon fixed-width"
        :class="a.colour">
        <span class="icon" :style="{'background-image': `url(${a.icon})`}"></span>
        {{a.title}}
      </span>
      <br>
    </span>
  </p> --%>
  
  
  <%-- The button to toggle mobile actions --%>
  <%-- <div v-if="isMobile" class="mobile-action-toggle" @click="toggleMobileActions">
    
    <p class="action"
      :class="mobileActionsToggled? 'toggle-off red' : 'toggle-on primary'">
      
      <span v-if="mobileActionsToggled">
        Cancel
      </span>
      <span v-else>
        Show
      </span>
    </p>
    
  </div> --%>
  
  
  
    
    <%-- <div v-if="cancelAction" id="map-cancel-button" @click="cancel">
        <p class="web button red hidden-xs"> <i class="icon fa fa-ban"></i> Cancel </p>
        <p class="button mobile visible-xs"> </p>
    </div> --%>
    
    <%-- <transition name="fade">
        <div class="map-overlay" v-if="overlayMessage != null">
            <p v-if="overlayMessage != ''" class="message">{{overlayMessage}}</p>
        </div>
    </transition> --%>
    
    <%-- <transition name="grow-fade">
        <div v-if="detail" id="map-detail">
            <h2 class="title">
                <span class="text">{{detail.title}}</span>
                <span class="menu">
                    <i @click="minifyDetail" class="mini-button fa"
                        :class="detail.minimized ? 'fa-plus-circle' : 'fa-minus-circle'" ></i>
                    <i @click="closeDetail" class="close-button fa fa-times-circle"></i>
                </span>
            </h2>
            
            <component :is="detail.component" v-if="!detail.minimized" class="inner"></component>
            
        </div>
    </transition> --%>
    
    <%-- <div id="map-controls" v-if="controlsEnabled">
        <h2 class="title"> Customise </h2>
        <div class="inner">
            <div v-for="c in controls" :id="c.id" class="control" v-html="c.contents"></div>
        </div>
    </div> --%>
    
    <%-- <transition name="grow-fade">
        <div v-if="mobileOptionsEnabled" id="mobile-buttons" class="active visible-xs">
            <p class="button actions" @click="addMobileActions"></p>
            <p class="button controls" v-if="controls.length > 0" @click="addMobileControls"></p>
        </div>
    </transition> --%>
    
    <%-- <transition name="grow-fade">
        <div v-if="actionsEnabled" id="map-actions">
            <p v-for="a in actions"
              :id="a.id"
              @click.prevent="a.onClick()"
              class="action with-icon fixed-width"
              :class="a.colour">
                <span class="icon"><i class="fa" :class="a.icon"></i></span>
                {{a.title}}
            </p>
        </div>
    </transition> --%>
    
    
    
    <%-- An element to render the map into --%>
    <%-- <div id="map" @click="mapClicked"></div> --%>
    <div id="map"></div>
    
</div>


<% if Tileset = 'Google' %>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={$SiteConfig.MapApiKey}&amp;">
    </script>
<% end_if %>


<script data-main="maps/javascript/map.js" src="maps/javascript/libs/require.js"></script>
