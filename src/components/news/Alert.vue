<template>
  <div class="alert">
    <h5>{{alert.title}}</h5>
    <p>{{alert.text}}</p>
  </div>
</template>

<script>
import moment from '@nextcloud/moment'

export default {
  name: 'News',
  props: {
    alert: Object,
    arrayid: Number
  },
  computed: {
    search: {
      get() {
        return this.$store.state.search
      },
      set(value) {
        clearTimeout(this.timer)
        this.timer = setTimeout(() => {
          this.$store.commit('setNewsUpdating', true)
        }, 250)
        this.$store.commit('updateSearch', value)
      }
    },
    getFormatedDate() {
      return moment((this.news.time * 1000)).format('LL')
    },
  },
  data: function () {
    return {
      newscolor: '#00B2FF',
      focus: '',
      timer: undefined,
    }
  }
}
</script>

<style scoped>
.alert {
  margin: 20px;
  position: relative;
  background: var(--color-mode-2);
}
.alert::before {
  content: " ";
  position: absolute;
  background: #f0f0f0;
  transform: rotate(2.26deg);
}
.alert::after {
  content: " ";
  position: absolute;
  background: #f0f0f0;
  transform: matrix(-1, 0.04, 0.04, 1, 0, 0);
}
</style>