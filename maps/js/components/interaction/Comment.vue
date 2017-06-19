<template lang="html">
  <div class="comment">
    
    <!-- The profile image of the commenter -->
    <profile-image></profile-image>
    
    <!-- The content of the comment -->
    <div class="content">
      
      <!-- The author & date -->
      <p class="heading">
        <span class="author">{{author}}</span>
        <span class="date">{{date}}</span>
      </p>
      
      <!-- The actual comment -->
      <p class="message"> {{comment.message}}</p>
    </div>
    
  </div>
</template>



<script>
import moment from 'moment'

export default {
  props: [ 'dataId', 'dataType', 'comment' ],
  computed: {
    author() { return 'Rob Anderson' },
    date() { return moment(this.comment.created).fromNow() }
  }
}
</script>



<style lang="css">
</style>
