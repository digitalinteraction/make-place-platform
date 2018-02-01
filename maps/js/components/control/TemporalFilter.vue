<template lang="html">
  <div class="control temporal-filter" :class="options.mode">
    <template v-if="options.mode === 'DayOfWeek'">
      <h5> Filter by Day of the week </h5>
      <label v-for="(day, i) in days">
        <input type="checkbox" v-model="chosenDays" :value="i" @change="updateFilter">
        {{day}}
      </label>
    </template>
    <template v-else-if="options.mode === 'DateRange'">
      <h5> Filter by date range </h5>
      <p>
        <label> From </label>
        <datepicker v-model="startDate" placeholder="From date" :monday-first="true" class="datepicker"></datepicker>
        <label> To </label>
        <datepicker v-model="endDate" placeholder="To date" :monday-first="true" class="datepicker"></datepicker>
      </p>
      <p class="text-right">
        <button class="primary" @click="updateFilter"> Apply </button>
        <button class="secondary" @click="clearRange"> Clear </button>
      </p>
    </template>
    <template v-else-if="options.mode === 'Recentness'">
      <h5> Filter recent responses </h5>
      <label>
        Show results in the last
        <input type="number" v-model.number="recentness" @change="updateFilter">
        days
      </label>
    </template>
  </div>
</template>

<script>
import responseService from '../../services/responses'
import Datepicker from 'vuejs-datepicker'

const DayNames = [
  'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
]

export default {
  components: { Datepicker },
  props: [ 'options' ],
  data() {
    return {
      days: DayNames,
      chosenDays: [],
      startDate: null,
      endDate: null,
      recentness: null
    }
  },
  mounted() {
    responseService.registerFilter(this.options.id, response => {
      return this.performFilter(response)
    })
  },
  methods: {
    updateFilter() {
      responseService.invalidate()
    },
    performFilter(response) {
      
      switch (this.options.mode) {
        case 'DayOfWeek':
          return this.chosenDays.length === 0 ||
          this.chosenDays.includes(response.created.getDay())
          
        case 'DateRange':
          if (!this.startDate && !this.endDate) return true
          if (!this.startDate) return response.created <= this.endDate
          if (!this.endDate) return response.created >= this.startDate
          return response.created >= this.startDate &&
            response.created <= this.endDate
            
        case 'Recentness':
          if (!this.recentness) return true
          let limit = new Date()
          limit.setHours(-this.recentness * 24, 0, 0, 0)
          return response.created >= limit
          
        default:
          return true
      }
      
    },
    clearRange() {
      this.startDate = null
      this.endDate = null
      this.updateFilter()
    }
  }
}
</script>

<style lang="scss" scoped>

.temporal-filter {
  
  
  
  &.DayOfWeek {
    label {
      margin-right: 0.5em;
      user-select: none;
    }
  }
  
  &.DateRange {
    .datepicker {
      display: inline-block;
      font-weight: 400;
    }
    button {
      @include button-style;
      font-size: 1em;
    }
  }
  
  &.Recentness {
    input[type=number] {
      width: 42px;
    }
  }
  
}

</style>
