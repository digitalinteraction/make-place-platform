<span @click.prevent="onClick"
  class="action with-icon"
  :class="colour">
  <span class="icon" :style="{'background-image': `url(${icon})`}"></span>
  {{title}}
</span>
