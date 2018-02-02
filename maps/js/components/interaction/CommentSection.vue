<template lang="html">
  <div class="comment-section">
    
    <h3 class="title"><slot></slot></h3>
    
    <comment-composer @commented="commented"
      v-if="canMake"
      :can-comment="canMake"
      :data-id="dataId"
      :data-type="dataType"
      :placeholder="placeholder"
      :action="action">
    </comment-composer>
    <p v-else class="disabled-msg primary-border">
      <span v-if="perms.comments.make === 'NoOne'">
        Comments have been disabled
      </span>
      <span v-else-if="perms.comments.make === 'Group'">
        You don't have permission to comment
      </span>
      <span v-else>
        <a href="/login" title="log in">Log in</a> to comment
      </span>
    </p>
    
    
    <div v-if="comments && canView">
      
      <!-- Display comments -->
      <comment v-for="c in comments" :key="c.id" :comment="c" :data-id="dataId" :data-type="dataType"></comment>
      
      <!-- Display a message  if no comments -->
      <p v-if="comments.length === 0" class="text-center"> No comments yet, be the first! </p>
    </div>
    
  </div>
</template>



<script>
import axios from 'axios'

export default {
  props: [ 'dataId', 'dataType', 'placeholder', 'action', 'canView', 'canMake', 'perms' ],
  data() {
    return {
      comments: null
    }
  },
  mounted() {
    this.fetchComments()
  },
  methods: {
    async fetchComments() {
      
      let res = await axios.get(`${this.$config.api}/api/comment/on/${this.dataType}/${this.dataId}`)
      
      this.comments = res.data
    },
    commented(comment) {
      this.comments.unshift(comment)
    }
  }
}
</script>



<style lang="scss">

.comment-section {
  
  .disabled-msg {
    padding: 0.5em;
    border-left-style: solid;
    border-left-width: 3px;
    border-right-color: white;
    border-top-color: white;
    border-bottom-color: white;
    font-size: 18px;
    color: $lightTextColour;
  }
}

</style>
