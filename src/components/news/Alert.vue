<template>
  <div class="alert">
    <div style="display:flex;justify-content:space-between">
      <svg
        width="23"
        height="23"
        viewBox="0 0 23 23"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <circle cx="11.2826" cy="11.5007" r="11" fill="#D9D9D9" />
        <circle cx="11.2826" cy="11.5007" r="8" fill="#919191" />
      </svg>
      <h5 style="color: var(--color-mode-contrast-1);text-align:center">{{alert.title}}</h5>
      <svg
        width="23"
        height="23"
        viewBox="0 0 23 23"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <circle cx="11.2826" cy="11.5007" r="11" fill="#D9D9D9" />
        <circle cx="11.2826" cy="11.5007" r="8" fill="#919191" />
      </svg>
    </div>
    <p style="color: var(--color-mode-contrast-4);" v-html="alert.text"></p>
    <div class="expiration-block">
      <div class="expiration-inner">
        <div class="expiration-date">Expire dans {{ getFormatedDate }}</div>
        <alert-admin-update :alert="alert" />
      </div>
    </div>
  </div>
</template>

<script>
import moment from '@nextcloud/moment'
import AlertAdminUpdate from './AlertAdminUpdate'

export default {
  name: 'News',
  components: {
    AlertAdminUpdate
  },
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
      var expiration = moment((this.alert.expiration * 1000))
      var diff = expiration.diff(moment(), 'days');
      if (diff == 0)
        return expiration.diff(moment(), 'hours') + " heures";
      if (diff == 1)
        return diff + " jour";
      return diff + " jours";
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
  margin: 5%;
  padding: 5px 10px;
  position: relative;
  background: var(--color-mode-2);
}
.alert::after,
.alert::before {
  content: " ";
  position: absolute;
  background-color: var(--color-mode-4);
  z-index: -1;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
}

.alert::before {
  transform: rotate(2.26deg);
}
.alert::after {
  transform: matrix(-1, 0.04, 0.04, 1, 0, 0);
}

.expiration-block {
  position: relative;
  background-color: transparent;
}
.expiration-inner {
  border-radius: 10px;
  width: 100%;
  height: 100%;
  position: relative;
  display: flex;
}
.expiration-date {
  width: fit-content;
  margin-left: auto;
  background: var(--color-mode-6);
  border-radius: 10px;
  padding: 0 10px 0 10px;
  position: relative;
  height: 100%;
  font-size: 13px;
  color: var(--color-mode-5);
  line-height: 26px;
}
</style>