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

<style lang="scss">

@import 'maps/sass/maps-common.scss';

@keyframes emoji-select {
  33%   { transform: scale(1.3); }
  100%  { transform: scale(1.0); }
}

@keyframes grow-border {
  from  { border-width: 0px; }
  to    { border-width: 4px; }
}

.vote-section {
  
  margin-bottom: 18px;
  
  .title {
    text-align: center;
    font-size: 16px;
    margin-top: 2em;
  }
  
  .control {
    display: block;
    text-align: center;
    position: relative;
    
    .holder {
      display: inline-block;
      background-color: transparentize($primaryColour, 0.90);
      box-shadow: 0px 0.5px 2px rgba(0,0,0,0.5);
      margin: 0;
      padding: 8px;
      height: 58px;
      border-radius: 28px;
      
      &:not(.enabled) {
        opacity: 0.3;
      }
    }
    
    .disabled-msg {
      position: absolute;
      top: 0;
      left: 0; right: 0;
      
      text-align: center;
      color: $darkColour;
      font-weight: 600;
      font-size: 20px;
      padding: 0.8em 0;
      text-shadow: 0 0 25px white;
    }
  }
  
  .emoji {
    display: block;
    position: relative;
    float: left;
    width: 42px;
    height: 42px;
    
    background-size: contain;
    background-repeat: no-repeat;
    
    &:not(:last-child) {
      margin-right: 6px;
    }
    
    transform: scale(1);
    transform-origin: center bottom;
    transition: transform 0.2s;
    
    .tip { display: none; }
    
    &.masked {
      background-color: $primaryColour;
      border-radius: 50%;
    }
    
    @include not-small {
      &.enabled:hover {
        cursor: pointer;
        transform: scale(1.15);
        
        .tip {
          @include text-style;
          font-size: 12px;
          background: #000;
          color: #fff;
          position: absolute;
          top: -24px;
          border-radius: 4px;
          display: block;
          left: 50%;
          transform: translateX(-50%);
          padding: 2px 8px;
          user-select: none;
        }
      }
    }
    &.current {
      animation-name: emoji-select;
      animation-fill-mode: both;
      animation-duration: 0.3s;
      animation-delay: 0.0s;
    }
    .backdrop {
      position: absolute;
      left: -3px;
      right: -3px;
      top: -3px;
      bottom: -3px;
      border-radius: 50%;
      border: 0px solid $secondaryColour;
      animation-name: grow-border;
      animation-duration: 0.3s;
      animation-fill-mode: both;
    }
  }
  
  .summary {
    text-align: center;
    font-weight: 700;
    color: #555;
    font-size: 12px;
  }
  
  
  .emoji-summary {
    img {
      width: 14px;
      height: 14px;
      
      &.masked {
        background-color: $primaryColour;
        border-radius: 50%;
      }
    }
    .count {
      margin-left: -2px;
      margin-right: 6px;
    }
  }
  
}

</style>
