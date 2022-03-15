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
        <img class="news-img" v-bind:src="news.photo" v-if="news.photo != ''" />
      </div>
      <div class="news-tagbox">
        <span class="news-tag" @click="search = '#' + news.category">{{ news.category }}</span>
        <button type="button" class="news-tagbox-button" @click="SetPinned(news)">
          <b-icon
            class="sidebar-item-icon"
            variant="dark"
            :icon="news.pinned == 1 ? 'shift' : 'shift-fill'"
          />
        </button>
        <div class="news-tagbox-button" v-if="news.visible == 0">
          <b-icon
            class="sidebar-item-icon"
            variant="dark"
            icon="eye-slash"
            @click="ChangeVisibility(news)"
          />
        </div>
        <button type="button" class="news-tagbox-button" @click="AdminOptions()">
          <b-icon class="sidebar-item-icon" variant="dark" icon="gear" />
        </button>
        <div class="flip-tagbox" :adminopt="adminopt">
          <div class="flip-tagbox-inner">
            <div class="news-tag-date">{{ getFormatedDate }}</div>
            <div class="admin-tagbox">
              <button type="button" class="news-visibility-button" @click="ChangeVisibility(news)">
                <b-icon
                  class="sidebar-item-icon"
                  variant="dark"
                  :icon="news.visible == 1 ? 'eye' : 'eye-slash'"
                />
              </button>
              <NewsUpdate :autocomplete="news" />
              <button type="button" class="news-del-button" @click="DeleteVerification(news)">
                <b-icon class="sidebar-item-icon" variant="danger" icon="trash" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import NewsUpdate from './news-update.bdd'
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'
import moment from '@nextcloud/moment'

export default {
  name: 'NewsCompAdmin',
  components: {
    NewsUpdate
  },
  props: {
    news: Object,
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
    newfocus() {
      return this.$store.state.newsfocus;
    },
  },
  methods: {
    AdminOptions() {
      this.adminopt = !this.adminopt
    },
    OpenNews() {
      if (this.$store.state.newsfocus == '') {
        this.$store.commit('updateNewsFocus', this.arrayid)
      }
    },
    SetPinned(news) {
      this.$bvModal.msgBoxConfirm(`Titre de l'actualité : ${news.title}`, {
        title: news.pinned == 0 ? 'épingler cette actualité ? (si une autre actualité est épingler, celle ci prendra sa place)' : 'annuler l\'épinglage ?',
        id: 'newsmodal4',
        size: 'md',
        buttonSize: 'sm',
        okVariant: news.pinned == 0 ? 'success' : 'danger',
        okTitle: news.pinned == 0 ? 'Epingler' : 'Annuler l\'épinglage',
        cancelTitle: 'Retour',
        footerClass: 'p-2',
        hideHeaderClose: false,
        centered: true
      })
        .then(value => {
          if (value) {
            this.changePinned(news.id)
          }
        })
    },
    ChangeVisibility(news) {
      this.$bvModal.msgBoxConfirm(`Changement de visibilité de cette actualité : ${news.title}`, {
        title: news.visible == 0 ? 'cette actualité n\'est pas encore publiée , voulez-vous la publier ?' : 'cette actualité est publiée , voulez-vous la cacher ?',
        id: 'newsmodal3',
        size: 'md',
        buttonSize: 'sm',
        okVariant: news.visible == 0 ? 'success' : 'danger',
        okTitle: news.visible == 0 ? 'Publier' : 'Rendre invisible',
        cancelTitle: 'Retour',
        footerClass: 'p-2',
        hideHeaderClose: false,
        centered: true
      })
        .then(value => {
          if (value) {
            if (news.visible == 1) {
              this.changeVisNews(news.id, 0)
            }
            else {
              this.changeVisNews(news.id, 1)
            }

          }
        })
    },
    DeleteVerification(news) {
      this.$bvModal.msgBoxConfirm(`Êtes-vous sûr de vouloir supprimer cette actualité : ${news.title}`, {
        title: 'Cette action est irréversible',
        id: 'menumodal3',
        size: 'sm',
        buttonSize: 'sm',
        okVariant: 'danger',
        okTitle: 'Supprimer',
        cancelTitle: 'Retour',
        footerClass: 'p-2',
        hideHeaderClose: false,
        centered: true
      })
        .then(value => {
          if (value) {
            this.deleteNews(news.id)
          }
        })
    },
    async changePinned(id) {
      try {
        var url = `apps/intranetagglo/news/pin/${id}`
        const response = await axios.post(generateUrl(url), { 'id': id }, { type: 'application/json' })
        this.LastModifiedID = response.data.id
      } catch (e) {
        console.error(e)
      }
      this.$store.commit('setNewsUpdating', true)
    },
    async changeVisNews(id, visible) {
      try {
        var url = `apps/intranetagglo/news/pub/${id}`
        const response = await axios.post(generateUrl(url), { 'id': id, 'visible': visible }, { type: 'application/json' })
        this.LastModifiedID = response.data.id
      } catch (e) {
        console.error(e)
      }
      this.$store.commit('setNewsUpdating', true)
    },
    async deleteNews(id) {
      try {
        var url = `apps/intranetagglo/news/${id}`
        const response = await axios.delete(generateUrl(url, { id }))
        this.LastModifiedID = response.data.id
      } catch (e) {
        console.error(e)
      }
      this.$store.commit('setNewsUpdating', true)
    },
  },
  data: function () {
    return {
      newscolor: '#00B2FF',
      focus: '',
      timer: undefined,
      adminopt: false
    }
  }
}
</script>