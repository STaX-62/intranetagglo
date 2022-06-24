<template>
  <div class="news-update-button" @click="Modify()">
    <b-icon class="sidebar-item-icon" variant="danger" icon="gear" />
    <b-modal id="newsmodal1" size="xl" v-model="modal" ref="modal" @ok="UpdateAlert">
      <template #modal-title>
        Ajouter une
        <code style="font-size: 1.25rem;">Alerte</code>
      </template>
      <label for="titre">Titre</label>
      <b-form-input name="titre" v-model="autocomplete.title" required></b-form-input>
      <label for="groups-component-select">Restrictions de Groupes d'utilisateurs</label>
      <b-form-tags
        name="groups-component-select"
        v-model="getFormatedGroups"
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
        v-model="newexpiration"
        :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
        locale="fr-FR"
        hide-header="true"
        :min="minDate"
        :initial-date="initialDate"
        placeholder="Date d'expiration"
        value-as-date
      ></b-form-datepicker>
      <label for="text">Contenu de l'alerte</label>
      <VueTrix name="text" inputId="editor1" v-model="autocomplete.text" placeholder="..." />
      <template #modal-footer="{ ok }">
        <b-button size="md" variant="danger" @click="DeleteAlert()">Supprimer</b-button>
        <b-button size="md" variant="success" @click="ok()">Modifier</b-button>
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
  name: 'AlertAdminUpdate',
  components: {
    VueTrix
  },
  props: {
    alert: Object
  },
  computed: {
    categoryoptions() {
      return this.$store.state.categoryoptions;
    },
    availableOptions() {
      return this.$store.state.groupsoptions.filter(opt => this.autocomplete.groups.indexOf(opt) === -1)
    },
    shortdesccount() {
      return (190 - this.shortdesc.length)
    },
    getFormatedGroups: {
      get() {
        console.log(this.autocomplete)
        return this.autocomplete.groups.split(';')
      },
      set(value) {
        this.autocomplete.groups = value.join(';')
      }
    },
    initialDate() {
      return new Date(autocomplete.expiration * 1000)
    }
  },
  methods: {
    Modify() {
      this.modal = !this.modal;
      this.autocomplete = this.alert;
    },
    UpdateAlert() {
      this.updateNews(this.autocomplete)
      this.autocomplete = {
        title: "",
        subtitle: "",
        text: "",
        photo: null,
        category: "",
        groups: "",
        link: "",
        expiration: null
      }
    },
    DeleteAlert() {
      this.$bvModal.msgBoxConfirm(`Êtes-vous sûr de vouloir supprimer cette actualité : ${this.autocomplete.title}`, {
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
            this.deleteNews()
          }
        })
    },
    AddToast(title, subject, variant) {
      this.$bvToast.toast(subject, {
        title: title,
        variant: variant,
        autoHideDelay: 10000,
        appendToast: false
      })
    },
    async updateNews(news) {
      let data = new FormData();
      data.append('title', news.title);
      data.append('subtitle', news.subtitle);
      data.append('text', news.text);
      data.append('photolink', news.photo);
      data.append('category', news.category);
      data.append('groups', news.groups);
      data.append('link', news.link);
      data.append('expiration', news.expiration);

      axios.post(generateUrl(`apps/intranetagglo/news/update/${news.id}`), data, {
        headers: {
          'accept': 'application/json',
          'Content-Type': `multipart/form-data; boundary=${data._boundary}`,
        }
      }).then(() => {
        this.$store.commit('setNewsUpdating', true)
        this.$bvToast.toast(`L'actualité '${news.title.length > 60 ? news.title.substring(0, 60) + '...' : news.title}' a été modifiée`, {
          title: 'Modification de l\'actualité',
          variant: 'success',
          autoHideDelay: 10000,
          appendToast: false
        })
      }).catch((error) => {
        this.$bvToast.toast(error.message, {
          title: 'Erreur durant la modification de l\'actualité',
          variant: 'danger',
          autoHideDelay: 10000,
          appendToast: false
        })
      })
    },
    async DeleteNews() {
      var url = `apps/intranetagglo/news/${this.autocomplete.id}`
      await axios.delete(generateUrl(url), { id: this.autocomplete.id }).then(() => {
        this.AddToast('Suppression de news', `L'actualité '${this.autocomplete.title.length > 60 ? title.substring(0, 60) + '...' : this.autocomplete.title}' a bien été supprimée`, 'success')
      }).catch((error) => {
        this.AddToast('Erreur durant la suppression de l\'actualité', error.message, 'danger')
      })
      this.$store.commit('setNewsUpdating', true)
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
      autocomplete: {
        title: "",
        subtitle: "",
        text: "",
        photo: null,
        category: "",
        groups: "",
        link: "",
        expiration: null
      },
      newexpiration: null,
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