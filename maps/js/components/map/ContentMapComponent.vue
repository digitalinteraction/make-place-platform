<script>

// type Options = {
//   id: number
//   type: string
//   created: string
//   actionColour: string
//   name: string
//   order: number
//   pageID: number
//   popupContent: html
//   popupTitle: string
// }

export default {
  props: ['options'],
  mounted() {
    // 
    // Add an action to view the content
    // 
    this.$store.commit('addAction', {
      title: this.options.popupTitle,
      icon: 'info',
      colour: this.options.actionColour,
      onClick: this.actionHandler
    })
  },
  methods: {
    actionHandler() {
      const params = { id: this.options.id }
      this.$router.push({ name: 'content', params })
    }
  },
  render: h => ''
}
</script>

<style lang="css">
</style>
