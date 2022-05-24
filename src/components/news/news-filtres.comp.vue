<template>
  <b-popover target="news-filtres" placement="bottomleft" triggers="hover focus">
    <template #title>Filtres</template>
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
