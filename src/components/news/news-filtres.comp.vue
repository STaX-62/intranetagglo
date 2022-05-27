<template>
  <b-popover target="news-filtres" placement="bottomleft" triggers="hover focus" :dark="darkmode">
    <template #title>Filtres</template>
    <b-form-group label="Par Date">
      <b-form-datepicker
        id="picker-début"
        v-model="datefilter.start"
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
        v-model="datefilter.end"
        :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
        :min="minEndDate"
        :max="maxEndDate"
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
  name: 'Filtres',
  store: store,
  computed: {
    darkmode() {
      return this.$store.state.darkmode
    },
    categoryoptions() {
      return this.$store.state.categoryoptions
    },
    minEndDate() {
      console.log(this.datefilter)
      var min = new Date(this.datefilter.start)
      min.setDate(min.getDate() + 1)
      return min
    },
    datefilter: {
      get() {
        return this.$store.state.datefilter
      },
      set(value) {
        console.log(value)
        this.$store.commit('updateDateFilter', value)
      }
    },
    categoryfilter: {
      get() {
        return this.$store.state.categoryfilter
      },
      set(value) {
        clearTimeout(this.timer)
        this.timer = setTimeout(() => {
          this.$store.commit('setNewsUpdating', true)
        }, 250)
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
      minStartDate: new Date(this.minDate),
      maxStartDate: maxStart,
      defaultStartDate: defaultStart,
      maxEndDate: today
    }
  }
}
</script>
