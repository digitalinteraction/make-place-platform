define([
  "vue", "text!templates/map-default.vue",
  "vue-components/MapAction"
], function(Vue, Template, MapAction) {
  
  // The constructor for our component
  return function() {
    
    // The component to create
    var component = {
      template: Template,
      components: {
        MapAction: new MapAction()
      },
      props: [ 'actions', 'isMobile' ],
      computed: {
        actions: function() {
          return this.$store.state.actions;
        }
      }
    };
    
    
    // Asign the component into the new object
    Object.assign(this, component);
  };
});
