<template>
  <div class="add-news-button" @click="modal = !modal">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
      <path
        d="M432 256c0 17.69-14.33 32.01-32 32.01H256v144c0 17.69-14.33 31.99-32 31.99s-32-14.3-32-31.99v-144H48c-17.67 0-32-14.32-32-32.01s14.33-31.99 32-31.99H192v-144c0-17.69 14.33-32.01 32-32.01s32 14.32 32 32.01v144h144C417.7 224 432 238.3 432 256z"
      />
    </svg>
    <b-modal id="newsmodal1" size="xl" v-model="modal" ref="modal" @ok="AddNews">
      <template #modal-title>
        Ajouter une
        <code style="font-size: 1.25rem;">Alerte</code>
      </template>
      <label for="titre">Titre</label>
      <b-form-input name="titre" v-model="news.title" required></b-form-input>
      <label for="groups-component-select">Restrictions de Groupes d'utilisateurs</label>
      <b-form-tags
        name="groups-component-select"
        v-model="news.groups"
        size="lg"
        class="mb-2"
        add-on-change
        no-outer-focus
      >
        <template v-slot="{ tags, inputAttrs, inputHandlers, disabled, removeTag }">
          <ul v-if="tags.length > 0" class="list-inline d-inline-block mb-2">
            <li v-for="tag in tags" :key="tag" class="list-inline-item">
              <b-form-tag
                @remove="removeTag(tag)"
                :title="tag"
                :disabled="disabled"
                variant="info"
              >{{ tag }}</b-form-tag>
            </li>
          </ul>
          <b-form-select
            v-bind="inputAttrs"
            v-on="inputHandlers"
            :disabled="disabled || availableOptions.length === 0"
            :options="availableOptions"
          >
            <template #first>
              <!-- This is required to prevent bugs with Safari -->
              <option disabled value>Choose a tag...</option>
            </template>
          </b-form-select>
        </template>
      </b-form-tags>
      <label for="expidationDatepicker">Date d'expiration de l'alerte:</label>
      <b-form-datepicker
        name="expirationDatepicker"
        v-model="news.expiration"
        :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
        locale="fr-FR"
        hide-header="true"
        :min="minDate"
        :initial-date="minDate"
        :state="verification"
        placeholder="Date d'expiration"
        value-as-date
      ></b-form-datepicker>
      <b-form-invalid-feedback :state="verification">Veuillez valider une date d'expiration</b-form-invalid-feedback>
      <label for="text">Contenu de l'alerte</label>
      <VueTrix name="text" inputId="editor1" v-model="news.text" placeholder="..." />
      <template #modal-footer="{ ok }">
        <b-button size="md" variant="success" @click="ok()">Ajouter</b-button>
      </template>
    </b-modal>
  </div>
</template>




<script>
/* eslint-disable */
import VueTrix from "vue-trix";
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'
import FormData from 'form-data'

export default {
  name: 'AlertAdminCreate',
  components: {
    VueTrix
  },
  computed: {
    categoryoptions() {
      return this.$store.state.categoryoptions;
    },
    availableOptions() {
      return this.$store.state.groupsoptions.filter(opt => this.news.groups.indexOf(opt) === -1)
    },
    shortdesccount() {
      return (190 - this.shortdesc.length)
    },
    verification() {
      return this.news.expiration != null
    },
  },
  methods: {
    AddNews() {
      this.news.author = this.$store.state.username
      this.news.groups = this.news.groups.join(';')
      this.createNews(this.news)
      this.news = {
        title: "",
        subtitle: "",
        text: "",
        photo: null,
        category: "",
        groups: [],
        link: "",
        expiration: null
      }
    },
    async createNews(news) {
      let data = new FormData();
      data.append('title', news.title);
      data.append('subtitle', news.subtitle);
      data.append('text', news.text);
      data.append('category', news.category);
      data.append('groups', news.groups);
      data.append('link', news.link);
      data.append('expiration', news.expiration.toISOString());

      axios.post(generateUrl(`apps/intranetagglo/news`), data, {
        headers: {
          'accept': 'application/json',
          'Content-Type': `multipart/form-data; boundary=${data._boundary}`,
        }
      }).then(() => {
        this.$store.commit('setNewsUpdating', true)
        this.$bvToast.toast(`L'actualité '${news.title.length > 60 ? news.title.substring(0, 60) + '...' : news.title}' a été créée`, {
          title: 'Création de l\'actualité',
          variant: 'success',
          autoHideDelay: 10000,
          appendToast: false
        })
      }).catch((error) => {
        this.$bvToast.toast(error.message, {
          title: 'Erreur durant la création de l\'actualité',
          variant: 'danger',
          autoHideDelay: 10000,
          appendToast: false
        })
      })
    },
    toggleModal() {
      this.$refs['modal'].toggle('#toggle-btn')
    },
    formatdesc(value) {
      return value.substring(0, 190);
    },
    addphotos(photos) {
      return photos.push(null)
    },
    addpdf(tpdf) {
      return tpdf.push(null)
    },
  },
  data: function () {
    const now = new Date()
    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate())
    return {
      modal: false,
      news: {
        title: "",
        subtitle: "",
        text: "",
        photo: null,
        category: "",
        groups: [],
        link: "",
        expiration: null
      },
      minDate: today
    }
  }
}
</script>
<style scoped>
.add-news-button {
  width: 20px;
  height: auto;
  position: relative;
  margin-left: 10px;
}
.add-news-button svg {
  fill: var(--color-mode-contrast-1);
  transition: color 0.2s;
}
</style>