<template lang="html">
  <div class="comment-composer">
    
    <!-- The profile image of the current member -->
    <profile-image></profile-image>
    
    <!-- The composer form -->
    <div class="content">
      
      <textarea v-model="message" name="message"></textarea>
      
      <p class="placeholder" v-if="message.length < 1"> I think that ... </p>
      
      <button class="send" @click="submitComment" :disabled="isSending || message.length < 3"> {{action}} </button>
      
    </div>
    
  </div>
</template>



<script>
import axios from 'axios'

export default {
  props: [ 'dataId', 'dataType', 'parent' ],
  data() {
    return {
      message: '',
      isSending: false
    }
  },
  computed: {
    action() { return this.isSending ? 'Sending' : 'Send' }
  },
  methods: {
    async submitComment() {
      
      try {
        // 'Enfore' a minimum comment length
        if (this.message.length < 3) return
        
        // Disable send button
        this.isSending = true
        
        // Post the comment
        let res = await axios.post(
          `${this.$config.api}/api/comment/on/${this.dataType}/${this.dataId}`,
          { message: this.message }
        )
        
        // Update the ui
        this.isSending = false
        this.message = ''
        
        // Emit the comment to our parent
        this.$emit('commented', res.data)
        
      }
      catch (e) { console.log(e) }
      
    }
  }
}
</script>



<style lang="css">
</style>
