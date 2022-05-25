<template>
  <b-popover target="news-filtres" placement="bottomleft" triggers="hover focus">
    <template #title>Filtres</template>
    <b-form-group
      label="Chercher par date"
      label-size="lg"
      label-class="font-weight-bold pt-0"
      class="mb-0"
    >
      <b-form-datepicker
        id="example-i18n-picker"
        v-model="value"
        locale="fr-FR"
        hide-header="true"
        class="mb-2"
        placeholder="Date de Début"
        style="width:150px;height:60px"
      ></b-form-datepicker>
      <b-form-datepicker
        id="example-i18n-picker"
        v-model="value"
        locale="fr-FR"
        hide-header="true"
        class="mb-2"
        placeholder="Date de Fin"
        style="width:150px;height:60px"
      ></b-form-datepicker>
    </b-form-group>
    <b-form-group
      label-cols-lg="3"
      label="Chercher par catégorie"
      label-size="lg"
      label-class="font-weight-bold pt-0"
      class="mb-0"
    >
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
