<template>
  <div class="news news-customcolor" v-bind:style="'--news-color: ' + newscolor">
    <div class="news-textbox">
      <div v-bind:id="'news-textbox-block' + news.id" class="news-textbox-block" :img="news.photo == '' ? 'no' : 'yes'" @click="OpenNews()">
        <div class="news-title">{{ news.title }}</div>
        <div class="news-subtitle" :class="{ 'active': isActive }">{{ news.subtitle }}</div>
        <div class="news-bar"></div>
        <img class="news-img-preview" v-bind:src="news.photo[0]" v-if="news.photo[0] != '' && newfocus == ''" />
        <div class="news-description" v-html="news.text"></div>
      </div>
      <div class="news-img-container">
        <b-carousel v-model="slide" v-if="photoMultiple && newfocus != ''" :interval="0" controls indicators no-animation style="text-shadow: 1px 1px 2px #333; width: 100%;">
          <b-carousel-slide v-for="p in news.photo" :key="p">
            <template v-slot:img>
              <img class="news-img" :src="p" style="height: auto;width: fit-content;" @click="visible = !visible">
            </template>
          </b-carousel-slide>
        </b-carousel>

        <img class="news-img" v-bind:src="news.photo[0]" v-if="!photoMultiple" @click="visible = !visible" style="width:auto" />
        <vue-easy-lightbox escDisabled moveDisabled :visible="visible" :imgs="news.photo" :index="slide" @hide="visible = !visible"></vue-easy-lightbox>
      </div>
      <div class="news-tagbox">
        <span class="news-tag" @click="addFilter(news.category)">{{ news.category }}</span>
        <button type="button" class="news-tagbox-button" @click="SetPinned(news)" v-if="news.visible == 1">
          <b-icon class="sidebar-item-icon" variant="dark" :icon="news.pinned == 0 ? 'shift' : 'shift-fill'" />
        </button>
        <div class="news-tagbox-button" v-if="news.visible == 0">
          <b-icon class="sidebar-item-icon" variant="dark" icon="eye-slash" @click="ChangeVisibility(news)" />
        </div>
        <button type="button" class="news-tagbox-button" @click="AdminOptions()">
          <b-icon class="sidebar-item-icon" variant="dark" icon="gear" />
        </button>
        <div class="flip-tagbox" :adminopt="adminopt">
          <div class="flip-tagbox-inner">
            <div class="news-tag-date">{{ getFormatedDate }}</div>
            <div class="admin-tagbox">
              <button type="button" class="news-visibility-button" @click="ChangeVisibility(news)">
                <b-icon class="sidebar-item-icon" variant="dark" :icon="news.visible == 1 ? 'eye' : 'eye-slash'" />
              </button>
              <news-admin-update :news="news" />
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
import NewsAdminUpdate from './NewsAdminUpdate'
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'
import moment from '@nextcloud/moment'

export default {
  name: 'NewsAdmin',
  components: {
    NewsAdminUpdate,
  },
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
    photoMultiple() {
      return this.news.photo.length > 1
    }
  },
  methods: {
    AdminOptions() {
      this.adminopt = !this.adminopt
    },
    AddToast(title, subject, variant) {
      this.$bvToast.toast(subject, {
        title: title,
        variant: variant,
        autoHideDelay: 10000,
        appendToast: false
      })
    },
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
            this.changePinned(news.id, news.title)
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
              this.changeVisNews(news.id, 0, news.title)
            }
            else {
              this.changeVisNews(news.id, 1, news.title)
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
            this.deleteNews(news.id, news.title)
          }
        })
    },
    async changePinned(id, title) {
      var url = `apps/intranetagglo/news/pin/${id}`
      await axios.post(generateUrl(url), { 'id': id }, { type: 'application/json' }).then(() => {
        this.AddToast('Actualité éplinglée', `L'actualité '${title.length > 60 ? title.substring(0, 60) + '...' : title}' est désormais épinglée`, 'success')
      }).catch((error) => {
        this.AddToast('Erreur durant l\'épinglage de l\'actualité', error.message, 'danger')
      })
      this.$store.commit('setNewsUpdating', true)
    },
    async changeVisNews(id, visible, title) {
      var url = `apps/intranetagglo/news/pub/${id}`
      await axios.post(generateUrl(url), { 'id': id, 'visible': visible }, { type: 'application/json' }).then(() => {
        this.AddToast('Changement de visibilité de l\'actualité', `L'actualité '${title.length > 60 ? title.substring(0, 60) + '...' : title}' est désormais ${visible ? 'visible' : 'cachée'}`, 'success')
      }).catch((error) => {
        this.AddToast('Erreur durant le changement de visibilité de l\'actualité', error.message, 'danger')
      })
      this.$store.commit('setNewsUpdating', true)
    },
    async deleteNews(id, title) {
      var url = `apps/intranetagglo/news/${id}`
      await axios.delete(generateUrl(url, { id: id })).then(() => {
        this.AddToast('Suppression de news', `L'actualité '${title.length > 60 ? title.substring(0, 60) + '...' : title}' a bien été supprimée`, 'success')
      }).catch((error) => {
        this.AddToast('Erreur durant la suppression de l\'actualité', error.message, 'danger')
      })
      this.$store.commit('setNewsUpdating', true)
    },
  },
  data: function () {
    return {
      newscolor: '#00B2FF',
      focus: '',
      timer: undefined,
      adminopt: false,
      visible: false,
      slide: 0,
    }
  }
}
</script>