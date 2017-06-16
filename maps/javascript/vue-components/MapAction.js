define([
  "vue", "text!templates/map-action.vue"
], function(Vue, Template) {
  
  // The constructor for our component
  return function() {
    
    // The component to create
    var component = {
      template: Template,
      props: [ 'action' ],
      computed: {
        title: function() { return this.action.title || '??'; },
        icon: function() {
          var name = (this.action.icon || 'info');
          return  '/public/images/icons/'+name+'.svg';
        },
        colour: function() { return this.action.colour || 'primary'; }
      },
      methods: {
        onClick: function(e) {
          if (this.action.onClick) { this.action.onClick(e); }
        }
      }
    };
    
    
    // Asign the component into the new object
    Object.assign(this, component);
  };
});
