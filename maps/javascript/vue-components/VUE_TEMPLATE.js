define([
  "vue", "text!templates/TEMPLATE_NAME.vue"
], function(Vue, Template) {
  
  // The constructor for our component
  return function() {
    
    // The component to create
    var component = {
      template: Template
    };
    
    
    // Asign the component into the new object
    Object.assign(this, component);
  };
});
