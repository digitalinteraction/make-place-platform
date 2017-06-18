<template lang="html">
  
  <div class="voting">
    
    <h4 class="title"><slot></slot></h4>
    <div class="control">
      <div class="holder">
        
        <!-- Add our emoji -->
        <emoji v-for="(e,i) in emojiSet"
          :key="i"
          :emoji="e"
          @chosen="choseEmoji">
        </emoji>
        
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  props: [ 'dataId', 'dataType' ],
  data() {
    return {
      emojiSet: [
        { id: 1, name: 'Agree', icon: 'up' },
        { id: 2, name: 'Disgree', icon: 'down' },
        { id: 3, name: 'Love', icon: 'heart' },
        { id: 4, name: 'Wow', icon: 'wow' },
        { id: 5, name: 'Happy', icon: 'happy' },
        { id: 6, name: 'Sad', icon: 'sad' }
      ]
    }
  },
  methods: {
    choseEmoji(id, other) {
      
      console.log(other)
      
      let chosen = this.emojiSet.find((e) => { return e.id === id })
      
      if (chosen) {
        this.performVote(chosen)
      }
    },
    async performVote(emoji) {
      
      try {
        
        // Post to the api ...
        let res = await axios.post(
          `${this.$config.api}/api/vote/on/${this.dataType}/${this.dataId}`,
          { value: emoji.id }
        )
        
        
        // Create  a little emoji
        // Add above the control
        // Animate it upwards
        // Then remove it
        
        console.log('vote', res.data)
      }
      catch (error) {
        console.log(error)
      }
    }
  }
}
</script>

<style lang="css">
</style>
