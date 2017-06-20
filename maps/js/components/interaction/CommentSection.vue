<template lang="html">
  <div class="comment-section">
    
    <h3 class="title"><slot></slot></h3>
    
    <comment-composer @commented="commented"
      :data-id="dataId"
      :data-type="dataType"
      :placeholder="placeholder"
      :action="action">
    </comment-composer>
    
    <!-- <loading v-if="!comments" type="short">Fetching Comments</loading> -->
    <div v-if="comments">
      
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
  props: [ 'dataId', 'dataType', 'placeholder', 'action' ],
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



<style lang="css">
</style>
