<template lang="html">
  <div class="comment-composer">
    
    <!-- The profile image of the current member -->
    <profile-image></profile-image>
    
    <!-- The composer form -->
    <div v-if="canComment" class="content">
      <textarea v-model="message" maxlength="250" name="message" @keyup.enter.prevent="submitComment"></textarea>
      
      <p class="placeholder" v-if="message.length < 1"> {{placeholder}} </p>
      
      <button class="send" @click="submitComment" :disabled="isSending || message.length < 3"> {{action}} </button>
    </div>
    <div v-else class="content">
      
      <!-- <p>{{window}}</p> -->
      
      <p class="login-message">
        <a class="bubble" :href="loginUrl">Log in</a> to vote and comment
      </p>
      
    </div>
    
  </div>
</template>



<script>
import axios from 'axios'

export default {
  props: {
    dataId: Number,
    dataType: String,
    parent: Object,
    placeholder: { type: String, default: 'I think that ...' },
    action: { type: String, default: 'Send' },
    canComment: { type: Boolean, default: false }
  },
  data() {
    return {
      message: '',
      isSending: false
    }
  },
  computed: {
    actionTitle() { return this.isSending ? 'Sending' : this.action },
    loginUrl() { return `/login?&BackURL=${encodeURIComponent(window.location.pathname)}` }
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



<style lang="scss">

@import 'maps/sass/maps-common.scss';

.comment-composer {
  @include comment-content;
  @include comment-bottom;
  
  textarea {
    -webkit-appearance: none;
    border-radius: 4px;
    border: 2px solid #dadada;
    margin: 0px;
    height: 64px;
    width: 100%;
    resize: none;
    padding: 6px;
    font-size: 16px;
    padding-right: 68px;
    
    transition: border-color 0.3s ease;
    
    &:focus {
      outline: 0;
      border-color: $primaryColour;
    }
  }
  
  .placeholder {
    position: absolute;
    pointer-events: none;
    top: 8px;
    left: 8px;
    color: $placeholderTextColour;
    font-style: italic;
    font-size: 16px;
  }
  
  
  .send {
    @include reset-style;
    @include text-style();
    
    position: absolute;
    top: 8px;
    right: 8px;
    color: white;
    padding: 4px 12px;
    border-radius: 4px;
    
    background: $primaryColour;
    opacity: 1.0;
    
    transition: background-color 0.1s, opacity 0.1s;
    
    
    &:disabled {
      background: #ccc; opacity: 0.5;
      opacity: 0.5;
    }
  }
  
  .login-message {
    font-size: 20px;
    margin: 24px 0;
    text-align: center;
    color: #888;
  }
}

</style>
