<template>
  <b-popover target="news-filtres" placement="bottomleft" triggers="hover focus" :dark="darkmode">
    <template #title>Filtres</template>
    <b-form-group label="Par Date">
      <b-form-datepicker
        id="picker-début"
        v-model="startDate"
        :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
        locale="fr-FR"
        hide-header="true"
        size="sm"
        :value="defaultStartDate"
        :min="minStartDate"
        :max="maxStartDate"
        :initial-date="minStartDate"
        placeholder="Date de Début"
        value-as-date
      ></b-form-datepicker>
      <b-form-datepicker
        id="picker-fin"
        v-model="endDate"
        :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
        :min="minEndDate"
        :max="maxEndDate"
        :initial-date="maxEndDate"
        locale="fr-FR"
        hide-header="true"
        size="sm"
        placeholder="Date de Fin"
        value-as-date
      ></b-form-datepicker>
    </b-form-group>
    <b-form-group label="Par catégorie">
      <b-form-checkbox-group v-model="categoryfilter" name="categories">
        <b-form-checkbox
          :value="category"
          v-for="(category,index) in categoryoptions"
          :key="index"
        >{{category}}</b-form-checkbox>
      </b-form-checkbox-group>
    </b-form-group>
  </b-popover>
</template>

<script>
import store from '@/store/index.js'
export default {
  name: 'NewsFiltrage',
  store: store,
  computed: {
    darkmode() {
      return this.$store.state.darkmode
    },
    categoryoptions() {
      return this.$store.state.categoryoptions
    },
    minEndDate() {
      var min = new Date(this.startDate)
      min.setDate(min.getDate() + 1)
      return min
    },
    minStartDate() {
      return new Date(this.minDate * 1000)
    },
    startDate: {
      get() {
        return this.$store.state.datefilter.start
      },
      set(value) {
        this.$store.commit('updateStartDate', value)
      }
    },
    endDate: {
      get() {
        return this.$store.state.datefilter.end
      },
      set(value) {
        this.$store.commit('updateEndDate', value)
      }
    },
    categoryfilter: {
      get() {
        return this.$store.state.categoryfilter
      },
      set(value) {
        this.$store.commit('updateCategories', value)
      }
    }
  },
  props: {
    minDate: Number
  },
  data: function () {
    const now = new Date()
    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate())
    const maxStart = new Date(today)
    maxStart.setDate(maxStart.getDate() - 1)
    const defaultStart = new Date(today)
    defaultStart.setMonth(defaultStart.getMonth() - 3)
    return {
      selected: [],
      maxStartDate: maxStart,
      defaultStartDate: defaultStart,
      maxEndDate: today
    }
  }
}
</script>
