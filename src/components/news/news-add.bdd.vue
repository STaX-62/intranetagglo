<template>
  <div class="add-news-button" @click="modal = !modal">
    Ajouter une actualité
    <b-modal size="xl" v-model="modal" ref="modal">
      <template #modal-title>
        Ajouter une
        <code style="font-size: 1.25rem;">Actualité</code>
      </template>
      <form ref="form" @submit.stop.prevent="handleSubmit">
        <b-table-simple hover small caption-top stacked>
          <caption>sont obligatoires : Titre, Le contenu de l'actualité</caption>
          <b-tbody>
            <b-tr>
              <b-th>
                <b-form-group
                  label="Titre"
                  label-for="titre-input"
                  invalid-feedback="Name is required"
                >
                  <b-form-input id="titre-input" required></b-form-input>
                </b-form-group>
              </b-th>
              <b-th>
                <b-form-group
                  label="Groupes Nécessaires"
                  label-for="name-input"
                  invalid-feedback="Name is required"
                >
                  <b-form-input id="name-input" required></b-form-input>
                </b-form-group>
              </b-th>
              <b-th>
                <b-form-group
                  label="Categorie"
                  label-for="category-input"
                  invalid-feedback="Une categorie est necessaire"
                >
                  <b-form-select
                    id="category-input"
                    v-model="categoryselected"
                    :options="categoryoptions"
                    required
                  ></b-form-select>
                </b-form-group>
              </b-th>
            </b-tr>
            <b-tr>
              <b-th>
                <b-form-group
                  v-bind:label="'Sous-titre ( caractères restant:' + shortdesccount +' )'"
                  label-for="short-description-input"
                  invalid-feedback="Name is required"
                >
                  <b-form-textarea
                    id="short-description-input"
                    v-model="shortdesc"
                    placeholder="Entrer ici la courte description qui s'affichera dans la case de l'actualité (Max 190 caractères)..."
                    rows="3"
                    max-rows="3"
                    :formatter="formatdesc"
                  ></b-form-textarea>
                </b-form-group>
              </b-th>
            </b-tr>
            <b-tr>
              <b-th colspan="4">
                <b-form-group
                  label="Contenu"
                  label-for="description-input"
                  invalid-feedback="Name is required"
                >
                  <VueTrix
                    inputId="editor1"
                    v-model="editorContent"
                    placeholder="Contenu de l'actualité une fois étendue..."
                  />
                </b-form-group>
              </b-th>
            </b-tr>
            <b-tr>
              <b-th>
                <b-form-group label="Photos" label-size="xl">
                  <b-form-file
                    id="photo-input"
                    size="sm"
                    placeholder="Choisir le fichier (.jpg/.jpeg/.png)..."
                    drop-placeholder="Placer l'image ici ..."
                    multiple
                  ></b-form-file>
                </b-form-group>
              </b-th>
            </b-tr>
          </b-tbody>
          <b-tfoot></b-tfoot>
        </b-table-simple>
      </form>
      <template #modal-footer="{ ok, cancel }">
        <!-- Emulate built in modal footer ok and cancel button actions -->
        <b-button size="md" variant="danger" @click="cancel()">Annuler</b-button>
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

export default {
  name: 'NewsAdd',
  components: {
    VueTrix
  },
  computed: {
    shortdesccount() {
      return (190 - this.shortdesc.length)
    },
    categoryoptions() {
      var News = this.$store.state.News
      var CategoryArray = []
      for (var i = 0; i < News.length; i++) {
        CategoryArray.push(News[i].category)
      }
      const uniqueCaterogy = Array.from(new Set(CategoryArray))
      return uniqueCaterogy
    },
  },
  methods: {
    AddNews() {

    },
    async createMenu(menu) {
      this.updating = true
      try {
        var url = `apps/intranetagglo/news`
        const response = await axios.post(generateUrl(url), menu, { type: 'application/json' })
        this.LastModifiedID = response.data.id
      } catch (e) {
        console.error(e)
      }
      this.updating = false
    },
    showModal() {
      this.$refs['my-modal'].show()
    },
    hideModal() {
      this.$refs['my-modal'].hide()
    },
    toggleModal() {
      // We pass the ID of the button that we want to return focus to
      // when the modal has hidden
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
    }
  },
  data: function () {
    return {
      modal: false,
      newsaddhover: false,
      shortdesc: '',
      photos: [],
      tpdf: [],
      editorContent: '',
      categoryselected: ''
    }
  }
}
</script>