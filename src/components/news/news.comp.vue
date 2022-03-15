<template>
  <div class="news news-customcolor" v-bind:style="'--news-color: ' + newscolor">
    <div class="news-textbox">
      <div
        v-bind:id="'news-textbox-block' + news.id"
        class="news-textbox-block"
        :noimg="news.photo == '' ? true : false"
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
        <button type="button" class="news-pin-button" @click="SetPinned(news)" v-if="isAdmin">
          <b-icon class="sidebar-item-icon" variant="dark" icon="shift" v-if="news.pinned == 1" />
          <b-icon class="sidebar-item-icon" variant="dark" icon="shift-fill" v-else />
        </button>
        <button
          type="button"
          class="news-visibility-button"
          @click="ChangeVisibility(news)"
          v-if="isAdmin"
        >
          <b-icon class="sidebar-item-icon" variant="dark" icon="eye" v-if="news.visible == 1" />
          <b-icon class="sidebar-item-icon" variant="dark" icon="eye-slash" v-else />
        </button>
        <NewsUpdate v-if="isAdmin" :autocomplete="news" />
        <button
          type="button"
          class="news-del-button"
          @click="DeleteVerification(news)"
          v-if="isAdmin"
        >
          <b-icon class="sidebar-item-icon" variant="danger" icon="trash" />
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import NewsUpdate from './news-update.bdd'
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'

export default {
  name: 'NewsComp',
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
    isAdmin() {
      return this.$store.state.usergroups.includes('admin')
    },
    newfocus() {
      return this.$store.state.newsfocus;
    },
  },
  methods: {
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
        okVariant: news.visible == 0 ? 'success' : 'danger',
        okTitle: news.visible == 0 ? 'Epingler' : 'Annuler l\'épinglage',
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
            if (news.pinned == 1) {
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
  mounted() {
    let textboxblock = document.getElementById('news-textbox-block' + this.news.id)
    if (this.news.photos == "") textboxblock.setAttribute('noimg', 'true')
    else textboxblock.setAttribute('noimg', 'false')
  },
  data: function () {
    return {
      newscolor: '#00B2FF',
      focus: '',
      timer: undefined
    }
  }
}
</script>

<style scoped>
.news {
  position: relative;
  width: 30%;
  max-height: 100%;
  border-radius: 10px;
  background-color: #fff;
  border: 2px solid var(--color-mode-4);
  font-size: 18px;
  overflow: hidden;
  cursor: pointer;
  box-shadow: 0 4px 21px -12px var(--color-mode-shadow-4);
  transition: box-shadow 0.2s ease, transform 0.2s ease, height 1s ease;
}
.news-row[focus="right"] .news,
.news-row[focus="left"] .news,
.news-row[focus="center"] .news {
  cursor: default;
}

.news-textbox-block[noimg="false"] {
  flex: 0 0 calc(40% - 20px);
  height: 100%;
  /* display: grid;
  grid-template-columns: 100%;
  grid-template-rows: max-content auto max-content max-content auto;
  grid-template-areas:
    "."
    "."
    "."
    "."
    "."; */
}
.news-textbox-block {
  display: flex;
  flex-direction: column;
}

.news-row[focus=""] .news-textbox-block * {
  cursor: pointer;
}
.news-textbox-block[noimg="true"] {
  flex: 0 0 100%;
  height: 100%;
}
.news-row[focus="right"] .news-description,
.news-row[focus="left"] .news-description,
.news-row[focus="center"] .news-description {
  overflow: auto !important;
}
.news-row[focus="right"] .news-textbox-block,
.news-row[focus="left"] .news-textbox-block,
.news-row[focus="center"] .news-textbox-block {
  padding: 12px 12px 40px 12px;
}

.news-row[focus="right"] .news-textbox-block .news-img,
.news-row[focus="left"] .news-textbox-block .news-img,
.news-row[focus="center"] .news-textbox-block .news-img {
  display: none;
}

.news-row[focus=""] .news-textbox-block:after {
  content: "";
  position: absolute;
  z-index: 1;
  bottom: 0;
  left: 0;
  pointer-events: none;
  background-image: linear-gradient(
    to bottom,
    rgba(255, 255, 255, 0),
    rgba(255, 255, 255, 1) 90%
  );
  width: 100%;
  height: 4em;
}
.news-row[focus="right"] .news:hover,
.news-row[focus="left"] .news:hover,
.news-row[focus="center"] .news:hover,
.news-row[focus="left"] .news:hover .news-img,
.news-row[focus="center"] .news:hover .news-img,
.news-row[focus="right"] .news:hover .news-img {
  transform: none !important;
}
.news-row[focus="right"] .news-bar,
.news-row[focus="left"] .news-bar,
.news-row[focus="center"] .news-bar {
  width: 128px;
  transition: none;
}
.news-row[focus="right"]:hover .news-bar,
.news-row[focus="left"]:hover .news-bar,
.news-row[focus="center"]:hover .news-bar {
  width: 128px;
}

.news:hover {
  box-shadow: 0 34px 32px -33px rgba(0, 0, 0, 0.18);
  transform: translate(0px, -3px);
}
.news-img {
  display: flex;
  max-height: 100% !important;
  max-width: 100% !important;
  transition: transform 0.2s ease !important;
  overflow: hidden !important;
  padding-right: 12px !important;
  margin: auto;
  align-content: center;
  align-items: center;
}
.news-img-preview {
  display: flex;
  max-height: 100% !important;
  max-width: 100% !important;
  transition: transform 0.2s ease !important;
  overflow: hidden !important;
  padding-right: 12px !important;
  z-index: 2 !important;
  margin: auto;
  align-content: center;
  align-items: center;
  flex-shrink: 0;
}
.news:hover .news-img {
  transform: scale(1.05) rotate(0.5deg) !important;
}
.news:hover .news-bar {
  width: 40%;
}
.news-row[focus=""] .news-textbox {
  position: relative;
  padding: 12px 12px 40px 12px;
  width: 100%;
  height: 100%;
  font-size: 17px;
}
.news-row[focus="right"] .news-textbox,
.news-row[focus="left"] .news-textbox,
.news-row[focus="center"] .news-textbox {
  width: auto;
  display: flex;
  width: 100%;
  height: 100%;
}

.news-row[focus=""] .news-textbox {
  height: 100%;
  display: grid;
  grid-template-columns: 100%;
  grid-template-rows: minmax(0, 100%) max-content;
  overflow: hidden;
}
.news-row[focus="right"] .news-img-container,
.news-row[focus="left"] .news-img-container,
.news-row[focus="center"] .news-img-container {
  grid-area: Img !important;
  flex-grow: 1;
  display: flex;
  height: 100%;
  width: 100%;
}

.news-row[focus="right"] .news-img-container .news-img,
.news-row[focus="left"] .news-img-container .news-img,
.news-row[focus="center"] .news-img-container .news-img {
  z-index: 200;
}

.news-row[focus=""] .news-img-container {
  display: none;
}

.news-textbox * {
  position: relative;
}
.news-title {
  font-family: "Voces", "Open Sans", arial, sans-serif;
  font-size: 24px;
}
.news-subtitle {
  font-family: "Voces", "Open Sans", arial, sans-serif;
  color: #888;
}
.news-bar {
  left: -2px;
  width: 20%;
  height: 5px;
  padding: 5px;
  margin-top: 5px;
  margin-bottom: 5px;
  border-radius: 5px;
  background-color: #424242;
  transition: width 0.2s ease;
  padding: 2px;
}
.news-blue .news-bar {
  background-color: #0088ff;
}
.news-blue::before {
  background-image: linear-gradient(-70deg, #0088ff, transparent 50%);
}
.news-red .news-bar {
  background-color: #d62f1f;
}
.news-red::before {
  background-image: linear-gradient(-70deg, #d62f1f, transparent 50%);
}
.news-green .news-bar {
  background-color: #40bd00;
}
.news-green::before {
  background-image: linear-gradient(-70deg, #40bd00, transparent 50%);
}
.news-yellow .news-bar {
  background-color: #f5af41;
}
.news-yellow::before {
  background-image: linear-gradient(-70deg, #f5af41, transparent 50%);
}
.news-orange .news-bar {
  background-color: #ff5722;
}
.news-orange::before {
  background-image: linear-gradient(-70deg, #ff5722, transparent 50%);
}
.news-brown .news-bar {
  background-color: #c49863;
}
.news-brown::before {
  background-image: linear-gradient(-70deg, #c49863, transparent 50%);
}
.news-grey .news-bar {
  background-color: #424242;
}
.news-grey::before {
  background-image: linear-gradient(-70deg, #424242, transparent 50%);
}
.news-customcolor .news-bar {
  background-color: var(--news-color);
}
.news-customcolor::before {
  background-image: linear-gradient(-70deg, var(--news-color), transparent 50%);
}

.news-textbox-block[noimg="true"] .news-description {
  overflow: hidden;
  font-size: 15px;
  height: calc(100% - 60px);
  margin: 5px;
  color: #424242;
  text-overflow: ellipsis;
}
.news-textbox-block[noimg="false"] .news-description {
  overflow: hidden;
  font-size: 15px;
  height: 100%;
  margin: 5px;
  color: #424242;
  text-overflow: ellipsis;
}
.news-tagbox {
  position: absolute;
  border-radius: 10px;
  padding: 0 20px;
  display: flex;
  align-items: center;
  height: 40px;
  bottom: 0;
  font-size: 14px;
  cursor: default;
  user-select: none;
  background: #fff;
  width: 100%;
  z-index: 100;
}
.news-tag {
  display: inline-block;
  height: 1.7rem;
  font-size: 13px;
  background: #e0e0e0;
  color: #777;
  border-radius: 3px 0 0 3px;
  line-height: 26px;
  padding: 0 10px 0 23px;
  position: relative;
  margin-right: 20px;
  cursor: pointer;
  user-select: none;
  transition: color 0.2s;
}
.news-tag::before {
  content: "";
  position: absolute;
  background: #fff;
  border-radius: 10px;
  box-shadow: inset 0 1px rgba(0, 0, 0, 0.25);
  height: 6px;
  left: 10px;
  width: 6px;
  top: 10px;
}
.news-tag::after {
  content: "";
  position: absolute;
  border-bottom: 13px solid transparent;
  border-left: 10px solid #e0e0e0;
  border-top: 13px solid transparent;
  right: -10px;
  top: 0;
}
</style>