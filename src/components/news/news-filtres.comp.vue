<template>
  <b-popover target="news-filtres" placement="bottomleft" triggers="hover focus" :dark="darkmode">
    <template #title>Filtres</template>
    <div style="display:flex;justify-content:space-between;align-items:center">
      <b-form-datepicker
        id="picker-début"
        v-model="value"
        :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
        locale="fr-FR"
        hide-header="true"
        size="sm"
        placeholder="Date de Début"
      ></b-form-datepicker>
      <b-form-datepicker
        id="picker-fin"
        v-model="value"
        :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
        locale="fr-FR"
        hide-header="true"
        size="sm"
        placeholder="Date de Fin"
      ></b-form-datepicker>
    </div>
    <b-form-checkbox-group v-model="categoryfilter" name="categories">
      <b-form-checkbox
        :value="category"
        v-for="(category,index) in categoryoptions"
        :key="index"
      >{{category}}</b-form-checkbox>
    </b-form-checkbox-group>
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
    categoryfilter: {
      get() {
        return this.$store.state.categoryfilter
      },
      set(value) {
        clearTimeout(this.timer)
        this.timer = setTimeout(() => {
          this.$store.commit('setNewsUpdating', true)
        }, 250)
        console.log(value)
        this.$store.commit('updateCategories', value)
      }
    }
  },
  data: function () {
    return {
      selected: []
    }
  }
}
</script>
