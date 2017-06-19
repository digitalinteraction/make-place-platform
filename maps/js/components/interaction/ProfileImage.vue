<template lang="html">
  <div class="profile-image">
    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0, 0, 72, 72">
      <g>
        <path d="M36,72 C16.118,72 0,55.882 0,36 C0,16.118 16.118,0 36,0 C55.882,0 72,16.118 72,36 C72,55.882 55.882,72 36,72 z" fill="#EFEFEF"/>
        
        <path d="M36.193,47 C43.925,46.911 50.546,50.19 53.398,58.377 C48.265,62.221 42.105,63.828 36,64 C29.929,63.891 23.687,62.209 18.602,58.377 C21.539,49.708 28.295,47.315 36.193,47 z" fill="#CCCCCC"/>
        
        <path d="M36,44.75 C25.852,44.75 17.625,36.523 17.625,26.375 C17.625,16.227 25.852,8 36,8 C46.148,8 54.375,16.227 54.375,26.375 C54.375,36.523 46.148,44.75 36,44.75 z" fill="#CCCCCC"/>
        
        <path d="M36,0 C55.882,0 72,16.118 72,36 C72,55.882 55.882,72 36,72 C16.118,72 0,55.882 0,36 C0,16.118 16.118,0 36,0 z M36,2 C17.222,2 2,17.222 2,36 C2.244,44.049 4.719,52.582 10.563,58.426 L61.437,58.426 C67.359,52.482 69.59,44.117 70,36 C70,17.222 54.778,2 36,2 z" fill="#AAAAAA"/>
        
        <text transform="matrix(1, 0, 0, 1, 36.5, 64.5)">
          <tspan x="0" y="3.5" font-size="10" fill="#FFFFFF" text-anchor="middle">{{initials}}</tspan>
        </text>
      </g>
    </svg>
  </div>
</template>

<script>
export default {
  props: [ 'user' ],
  computed: {
    initials() {
      let alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
      
      let letters = ''
      while (letters.length < 2) {
        letters += alphabet[Math.floor(Math.random() * alphabet.length)]
      }
      
      return letters
    }
  }
}
</script>

<style lang="css">
</style>
