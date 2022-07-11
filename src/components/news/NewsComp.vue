<template>
  <div class="news news-customcolor" v-bind:style="'--news-color: ' + newscolor">
    <div class="news-textbox">
      <div
        v-bind:id="'news-textbox-block' + news.id"
        class="news-textbox-block"
        :img="news.photo == '' ? 'no' : 'yes'"
        @click="OpenNews()"
      >
        <div class="news-title">{{ news.title }}</div>
        <div class="news-subtitle" :class="{'active': isActive}">{{ news.subtitle }}</div>
        <div class="news-bar"></div>
        <img
          class="news-img-preview"
          v-bind:src="news.photo"
          v-if="news.photo != '' && newfocus == ''"
        />
        <div class="news-description" v-html="news.text"></div>
      </div>
      <div class="news-img-container">
        <img
          class="news-img"
          v-bind:src="news.photo"
          v-if="news.photo != ''"
          @click="visible = !visible"
        />
        <vue-easy-lightbox
          escDisabled
          moveDisabled
          :visible="visible"
          :imgs="news.photo"
          index="0"
          @hide="visible = !visible"
        ></vue-easy-lightbox>
      </div>
      <div class="news-tagbox">
        <span class="news-tag" @click="addFilter(news.category)">{{ news.category }}</span>
        <div class="news-tagbox-button" v-if="news.pinned == 1">
          <b-icon class="sidebar-item-icon" variant="dark" icon="shift-fill" />
        </div>
        <div class="flip-tagbox">
          <div class="flip-tagbox-inner">
            <div class="news-tag-date">{{ getFormatedDate }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from '@nextcloud/moment'

export default {
  name: 'NewsComp',
  props: {
    news: Object,
    arrayid: Number
  },
  computed: {
    categoryfilter: {
      get() {
        return this.$store.state.categoryfilter
      },
      set(value) {
        clearTimeout(this.timer)
        this.timer = setTimeout(() => {
          this.$store.commit('setNewsUpdating', true)
        }, 250)
        this.$store.commit('updateFilter', value)
      }
    },
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
    newfocus() {
      return this.$store.state.newsfocus;
    },
  },
  methods: {
    addFilter(category) {
      if (!this.categoryfilter.includes(category)) {
        this.categoryfilter.push(category)
      }
    },
    OpenNews() {
      if (this.$store.state.newsfocus == '' && this.news.link == "") {
        this.$store.commit('updateNewsFocus', this.arrayid)
      }
      if (this.news.link != "") {
        window.open(this.news.link, '_blank');
      }
    },
  },
  data: function () {
    return {
      newscolor: '#00B2FF',
      focus: '',
      timer: undefined,
      visible: false
    }
  }
}
</script>
