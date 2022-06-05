<template>
  <div class="add-news-button" @click="modal = !modal">
    Ajouter une actualité
    <b-modal id="newsmodal1" size="xl" v-model="modal" ref="modal" @ok="UpdNews">
      <template #modal-title>
        Modifier une
        <code style="font-size: 1.25rem;">Actualité</code>
      </template>
      <div v-if="step == 1" style="height:50vh">
        <label for="titre">Titre</label>
        <b-form-input name="titre" v-model="autocomplete.title" required></b-form-input>
        <label for="subtitle">Sous-titre</label>
        <b-form-input name="subtitle" v-model="autocomplete.subtitle" required></b-form-input>
        <label for="category">Catégorie</label>
        <b-form-input name="category" list="category-id" v-model="autocomplete.category" required></b-form-input>
        <datalist id="category-id">
          <option v-for="(category,index) in categoryoptions" :key="index">{{ category }}</option>
        </datalist>
        <label for="groups-component-select">Restrictions de Groupes d'utilisateurs</label>
        <b-form-tags
          name="groups-component-select"
          v-model="autocomplete.groups"
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
        <b-form-checkbox v-model="status" size="lg">Créer en tant qu'évènement</b-form-checkbox>
        <div v-if="status">
          <div style="font-weight:bold">
            Cette actualité ne s'affichera dans le pop-up "Evènements",
            qui s'ouvre automatiquement lorsque l'utilisateur a un évènement non lu
          </div>
          <label for="expidationDatepicker">Date d'expiration de l'évènement</label>
          <b-form-datepicker
            name="expirationDatepicker"
            v-model="autocomplete.expiration"
            :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
            locale="fr-FR"
            hide-header="true"
            :min="minDate"
            :initial-date="minDate"
            placeholder="Date d'expiration"
            value-as-date
          ></b-form-datepicker>
        </div>
      </div>
      <div v-if="step == 2 && !link" style="height:50vh">
        <label for="text">Contenu de l'actualité</label>
        <VueTrix
          name="text"
          inputId="editor1"
          v-model="autocomplete.text"
          placeholder="Contenu de l'actualité une fois étendue..."
        />
      </div>
      <div v-if="step == 2 && link" style="height:50vh">
        <label for="link">Lien de redirection de L'actualité</label>
        <b-form-input name="link" v-model="autocomplete.link"></b-form-input>
      </div>
      <div v-if="step == 3" style="height:50vh">
        <label for="photo">Image d'illustration / Photo</label>
        <b-form-file
          name="photo"
          size="sm"
          accept="image/*"
          placeholder="Choisir le fichier (.jpg/.jpeg/.png)..."
          drop-placeholder="Placer l'image ici ..."
          v-model="newimage"
          @change="onFileChange"
        ></b-form-file>
        <div style="height :80%">
          <div for="preview">Prévisualisation :</div>
          <img
            name="preview"
            v-if="autocomplete.photo != ''"
            :src="autocomplete.photo"
            style="max-width: 100%; max-height:calc(100% - 24px)"
          />
        </div>
      </div>
      <template #modal-footer="{ ok }">
        <div>Etape {{step}}/3</div>
        <b-button
          @click="link = !link"
          variant="dark"
          v-if="step == 2"
        >{{link ? "Remplacer par un lien" : "Remplacer par du Texte"}}</b-button>
        <b-button size="md" variant="secondary" @click="step--" v-if="step > 1">Précédent</b-button>
        <b-button size="md" variant="secondary" @click="step = step +1" v-if="step < 3">Suivant</b-button>
        <b-button size="md" variant="success" @click="ok()" v-if="step == 3">Ajouter</b-button>
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
  name: 'NewsAdminCreate',
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
  },
  methods: {
    UpdNews() {
      this.autocomplete.author = this.$store.state.username
      this.autocomplete.groups = this.news.groups.join(';')
      if (this.link) {
        this.autocomplete.text = ""
      }
      if (!this.status) {
        this.autocomplete.expiration = 0
      }
      this.updateNews(this.autocomplete, this.newimage)
      this.news = {
        title: "",
        subtitle: "",
        text: "",
        photo: null,
        category: "",
        groups: [],
        link: "",
        expiration: 0
      }
      this.step = 1
    },
    async updateNews(news, newimage) {
      let data = new FormData();
      data.append('title', news.title);
      data.append('subtitle', news.subtitle);
      data.append('text', news.text);
      if (newimage != null) {
        data.append('photo_upd', newimage, newimage.name);
      }
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
        expiration: 0
      },
      step: 0,
      link: false,
      minDate: today,
      status: false,
      newimage: null
    }
  }
}
</script>