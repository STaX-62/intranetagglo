<template>
  <div class="add-news-button" @click="modal = !modal">
    Ajouter une actualité
    <b-modal id="newsmodal1" size="xl" v-model="modal" ref="modal" @ok="AddNews">
      <template #modal-title>
        Ajouter une
        <code style="font-size: 1.25rem;">Actualité</code>
      </template>
      <form ref="form" @submit.stop.prevent="handleSubmit">
        <b-table-simple small caption-top stacked>
          <caption>sont obligatoires : Titre, Le contenu de l'actualité</caption>
          <b-tbody>
            <b-tr>
              <b-th>
                <b-form-input id="titre-input" v-model="news.title" required></b-form-input>
              </b-th>
              <b-th>
                <b-form-input id="subtitle-input" v-model="news.subtite" required></b-form-input>
              </b-th>
              <b-th>
                <b-form-select
                  id="category-input"
                  v-model="news.category"
                  :options="categoryoptions"
                  required
                ></b-form-select>
              </b-th>
            </b-tr>
            <b-tr>
              <b-th>
                <b-form-tags
                  id="tags-component-select"
                  name="groups"
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
                <VueTrix
                  inputId="editor1"
                  v-model="news.text"
                  placeholder="Contenu de l'actualité une fois étendue..."
                />
              </b-th>
            </b-tr>
            <b-tr>
              <b-th>
                <b-form-file
                  id="photo-input"
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
      return this.groupsoptions.filter(opt => this.news.groups.indexOf(opt) === -1)
    },
    shortdesccount() {
      return (190 - this.shortdesc.length)
    },
    // categoryoptions() {
    //   var News = this.$store.state.News
    //   var CategoryArray = []
    //   for (var i = 0; i < News.length; i++) {
    //     CategoryArray.push(News[i].category)
    //   }
    //   const uniqueCaterogy = Array.from(new Set(CategoryArray))
    //   return uniqueCaterogy
    // },
  },
  methods: {
    AddNews() {
      
      console.log(this.news.photo)
      // this.news.author = this.$store.state.username
      // this.news.groups = this.news.groups.join(';')


      // this.createNews(this.news)
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
    }
  },
  mounted() {
    if (this.$store.state.groupsoptions == []) {
      axios.get(generateOcsUrl(`cloud/groups`, 2))
        .then((response) => {
          this.groupsoptions = response.data.ocs.data.groups
          this.$store.commit('setGroupsOptions', response.data.ocs.data.groups)
        })
    }
    else {
      this.groupsoptions = this.$store.state.groupsoptions;
    }
  },
  data: function () {
    return {
      modal: false,
      news: {
        author: "",
        title: "",
        subtitle: "",
        text: "",
        photo: null,
        category: "",
        groups: [],
        visible: false
      },
      groupsoptions: [],
      categoryoptions: []
    }
  }
}
</script>