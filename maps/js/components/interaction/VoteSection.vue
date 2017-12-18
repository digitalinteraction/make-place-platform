<template lang="html">
  
  <div class="vote-section">
    
    <h4 class="title"><slot></slot></h4>
    <div class="control">
      <div class="holder" :class="{enabled: canMake}">
        
        <!-- Add our emoji -->
        <emoji v-for="e in emojiList"
          :key="e.id"
          :emoji="e"
          :current="e.id === chosenEmoji"
          :enabled="canMake"
          @chosen="choseEmoji">
        </emoji>
      </div>
      <p v-if="!canMake" class="disabled-msg">
        <span v-if="perms.voting.make === 'NoOne'">
          Voting has been disabled
        </span>
        <span v-else-if="perms.voting.make === 'Group'">
          You don't have permission to vote
        </span>
        <span v-else>
          <a href="/login" title="log in">Log in</a> to vote
        </span>
      </p>
    </div>
    
    <p class="summary" v-if="canView">
      <span v-if="type ==='ARROW'">
        Total score: {{voteMap[1] - voteMap[-1]}}
      </span>
      <span v-else>
        <emoji-summary v-for="(v, i) in voteList" :key="i" :emoji="iconSet[v.value]" :count="v.count">
        </emoji-summary>
        <span v-if="voteList.length === 0"> No votes yet, be the first! </span>
      </span>
    </p>
    
  </div>
</template>

<script>
import axios from 'axios'

const emojiPath = file => `/static/img/emoji/${file}`

const IconSets = {
  BASIC: [
    { id: -1, icon: emojiPath('disagree-o.svg'), name: 'Disagree', masked: true },
    { id: 1, icon: emojiPath('agree-o.svg'), name: 'Agree', masked: true }
  ],
  ARROW: [
    { id: -1, icon: emojiPath('down-o.svg'), name: 'Downvote', masked: true },
    { id: 1, icon: emojiPath('up-o.svg'), name: 'Upvote', masked: true }
  ],
  EMOJI: [
    { id: 1, icon: emojiPath('agree-o.svg'), name: 'Agree', masked: true },
    { id: 2, icon: emojiPath('disagree-o.svg'), name: 'Disgree', masked: true },
    { id: 3, icon: emojiPath('heart.svg'), name: 'Love' },
    { id: 4, icon: emojiPath('wow.svg'), name: 'Wow' },
    { id: 5, icon: emojiPath('happy.svg'), name: 'Happy' },
    { id: 6, icon: emojiPath('sad.svg'), name: 'Sad' }
  ],
  HEART: [
    { id: 1, icon: emojiPath('heart.svg'), name: 'Favourite' }
  ]
}

export default {
  props: [ 'dataId', 'type', 'dataType', 'perms', 'canView', 'canMake' ],
  data() {
    return {
      chosenEmoji: null,
      voteMap: {},
      isVoting: false,
      iconSet: IconSets[this.type || 'BASIC'].reduce((map, icon) => {
        return Object.assign(map, { [icon.id]: icon })
      }, {})
    }
  },
  computed: {
    voteApi() { return `${this.$config.api}/api/vote/on/${this.dataType}/${this.dataId}` },
    emojiList() {
      return Object.keys(this.iconSet).map(id => {
        return this.iconSet[id]
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
      
      let chosen = this.iconSet[id]
      
      if (chosen) {
        this.performVote(chosen)
      }
    },
    async performVote(emoji) {
      
      if (this.isVoting) return
      
      this.isVoting = true
      
      try {
        
        // The value to submit, the id of the emoji or 0 if un-voting
        var value = this.chosenEmoji === emoji.id ? 0 : emoji.id
        
        // Post to the api ...
        let res = await axios.post(this.voteApi, { value })
        
        
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
      
      this.isVoting = false
    },
    async fetchVotes() {
      try {
        
        // Fetch current value
        this.chosenEmoji = (await axios.get(`${this.voteApi}/current`)).data.value
        
        let res = await axios.get(this.voteApi)
        
        // Generate a map of vote-id to count of votes
        let votes = this.emojiList.reduce((map, emoji) => {
          map[emoji.id] = 0
          return map
        }, {})
        
        
        // Sum up the votes
        res.data.forEach(vote => {
          if (votes[vote.value] !== undefined) votes[vote.value]++
        }, {})
        
        // Set the votemap to update the ui
        this.voteMap = votes
      }
      catch (error) { console.log(error) }
    }
  }
}
</script>

<style lang="css">
</style>
