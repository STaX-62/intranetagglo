<template>
  <div class="add-news-button" @click="modal = !modal">
    Ajouter une actualité
    <b-modal id="newsmodal1" size="xl" v-model="modal" ref="modal" @ok="AddNews">
      <template #modal-title>
        Ajouter une
        <code style="font-size: 1.25rem;">Actualité</code>
      </template>
      <form ref="form" @submit.stop.prevent="handleSubmit">
        <b-table-simple id="news-table" small caption-top stacked>
          <caption>sont obligatoires : Titre, Le contenu de l'actualité</caption>
          <b-tbody>
            <b-tr>
              <b-th>
                <label for="titre">Titre</label>
                <b-form-input name="titre" v-model="news.title" required></b-form-input>
              </b-th>
              <b-th>
                <label for="subtitle">Sous-titre</label>
                <b-form-input name="subtitle" v-model="news.subtite" required></b-form-input>
              </b-th>
              <b-th>
                <label for="category">Catégorie</label>
                <b-form-input name="category" list="category-id"  v-model="news.category" required></b-form-input>
                <datalist id="category-id">
                  <option v-for="(category,index) in categoryoptions" :key="index">{{ category }}</option>
                </datalist>
              </b-th>
            </b-tr>
            <b-tr>
              <b-th>
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
              </b-th>
            </b-tr>
            <b-tr>
              <b-th colspan="4">
                <label for="text">Contenu de l'actualité</label>
                <VueTrix
                  name="text"
                  inputId="editor1"
                  v-model="news.text"
                  placeholder="Contenu de l'actualité une fois étendue..."
                />
              </b-th>
            </b-tr>
            <b-tr>
              <b-th>
                <label for="photo">Image d'illustration / Photo</label>
                <b-form-file
                  name="photo"
                  size="sm"
                  accept="image/*"
                  placeholder="Choisir le fichier (.jpg/.jpeg/.png)..."
                  drop-placeholder="Placer l'image ici ..."
                  v-model="news.photo"
                ></b-form-file>
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
    availableOptions() {
      return this.$store.state.groupsoptions.filter(opt => this.news.groups.indexOf(opt) === -1)
    },
    shortdesccount() {
      return (190 - this.shortdesc.length)
    },
  },
  methods: {
    AddNews() {
      this.news.author = this.$store.state.username
      this.news.groups = this.news.groups.id.join(';')
      this.createNews(this.news)
    },
    async createNews(news) {
      try {
        var url = `apps/intranetagglo/news`
        const response = await axios.post(generateUrl(url), news, { type: 'application/json' })
        this.LastModifiedID = response.data.id
      } catch (e) {
        console.error(e)
      }
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
    return {
      modal: false,
      news: {
        author: "",
        title: "",
        subtitle: "",
        text: "",
        photo: "",
        category: "",
        groups: [],
        time: null,
        visible: 0
      },
      categoryoptions: []
    }
  }
}
</script>