<template lang="html">
  <div class="comment">
    
    <!-- The profile image of the commenter -->
    <profile-image :member="comment.member"></profile-image>
    
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
    author() {
      let first = this.comment.member.firstName || ''
      let surname = this.comment.member.surname || ''
      return `${first} ${surname}`
    },
    date() { return moment(this.comment.created).fromNow() }
  }
}
</script>



<style lang="scss">

@import "maps/sass/mixins/comments.scss";

@keyframes comment-appear {
  0% { transform: translateY(-20px); opacity: 0; }
  100% { transform: translate(0); opacity: 1; }
}

.comment {
  @include comment-content;
  
  animation-name: comment-appear;
  animation-duration: 0.6s;
  animation-fill-mode: both;
  
  @for $i from 0 through 10 {
    &:nth-child(#{$i}) { animation-delay: 0.1s * $i; }
  }
  
  &:not(:last-child) {
    @include comment-bottom;
  }
  
  .heading {
    margin-bottom: 0;
    font-weight: 700;
    font-size: 16px;
    color: $darkColour;
    
    .date { float: right; }
  }
    
  .message {
    margin-bottom: 0;
  }
}


</style>
