<template lang="html">
  
  <div class="vote-section">
    
    <h4 class="title"><slot></slot></h4>
    <div class="control">
      <div class="holder">
        
        <!-- Add our emoji -->
        <emoji v-for="e in emojiList"
          :key="e.id"
          :emoji="e"
          :current="e.id === chosenEmoji"
          @chosen="choseEmoji">
        </emoji>
        
      </div>
    </div>
    
    <p class="summary">
      <emoji-summary v-for="(v, i) in voteList" :key="i" :emoji="emojiSet[v.value]" :count="v.count">
      </emoji-summary>
      <span v-if="voteList.length === 0"> No votes yet, be the first! </span>
    </p>
    
  </div>
</template>

<script>
import axios from 'axios'

export default {
  props: [ 'dataId', 'dataType' ],
  data() {
    return {
      chosenEmoji: null,
      voteMap: {},
      emojiSet: {
        1: { id: 1, icon: '/public/images/emoji/up.svg', name: 'Agree' },
        2: { id: 2, icon: '/public/images/emoji/down.svg', name: 'Disgree' },
        3: { id: 3, icon: '/public/images/emoji/heart.svg', name: 'Love' },
        4: { id: 4, icon: '/public/images/emoji/wow.svg', name: 'Wow' },
        5: { id: 5, icon: '/public/images/emoji/happy.svg', name: 'Happy' },
        6: { id: 6, icon: '/public/images/emoji/sad.svg', name: 'Sad' }
      }
    }
  },
  computed: {
    emojiList() {
      return Object.keys(this.emojiSet).map(id => {
        return this.emojiSet[id]
      })
    },
    voteList() {
      return Object.keys(this.voteMap).map(value => {
        let count = this.voteMap[value]
        return { value, count }
      }).filter(vote => {
        return vote.count > 0
      })
    }
  },
  mounted() {
    this.fetchVotes()
  },
  methods: {
    choseEmoji(id, event) {
      
      let chosen = this.emojiSet[id]
      
      if (chosen) {
        this.performVote(chosen)
      }
    },
    async performVote(emoji) {
      try {
        
        // The value to submit, the id of the emoji or 0 if un-voting
        var value = this.chosenEmoji === emoji.id ? 0 : emoji.id
        
        // Post to the api ...
        let res = await axios.post(
          `${this.$config.api}/api/vote/on/${this.dataType}/${this.dataId}`,
          { value }
        )
        
        
        
        // Remove previous vote
        this.voteMap[this.chosenEmoji]--
        this.voteMap[emoji.id] += this.chosenEmoji === emoji.id ? 0 : 1
        
        
        // Update the chosen to animate
        this.chosenEmoji = res.data.value
        
        // Create  a little emoji
        // Add above the control
        // Animate it upwards
        // Then remove it
      }
      catch (error) { console.log(error) }
    },
    async fetchVotes() {
      try {
        let res = await axios.get(
          `${this.$config.api}/api/vote/on/${this.dataType}/${this.dataId}`
        )
        
        // Generate a map of vote-id to count of votes
        let votes = this.emojiList.reduce((map, emoji) => {
          map[emoji.id] = 0
          return map
        }, {})
        
        
        // Sum up the votes
        res.data.forEach(vote => {
          if (votes[vote.value] !== undefined) votes[vote.value]++
        }, {})
        
        this.voteMap = votes
      }
      catch (error) { console.log(error) }
    }
  }
}
</script>

<style lang="css">
</style>
