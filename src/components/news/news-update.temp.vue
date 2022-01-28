<template>
  <div>
    <button type="button" class="news-add-button" @click="modal = !modal">Ajouter une actualité</button>
    <b-modal size="xl" v-model="modal" ref="modal" hide-footer>
      <template #modal-title>
        Ajouter une
        <code style="font-size: 1.25rem;">Actualité</code>
      </template>
      <form ref="form" @submit.stop.prevent="handleSubmit">
        <b-table-simple hover small caption-top stacked>
          <caption>sont obligatoires : Titre, La description courte, Le contenu de l'actualité</caption>
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
                  v-bind:label="'Description courte ( caractères restant:' + shortdesccount +' )'"
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
            <b-tr>
              <b-th>
                <b-form-group label="PDFv" label-size="xl">
                  <b-form-file
                    id="pdf-input"
                    size="sm"
                    placeholder="Choisir le fichier (.pdf)..."
                    drop-placeholder="Placer le pdf ici..."
                    multiple
                  ></b-form-file>
                </b-form-group>
              </b-th>
            </b-tr>
          </b-tbody>
          <b-tfoot></b-tfoot>
        </b-table-simple>
      </form>
      <b-button class="mt-2 form-button-valid" variant="success" block type="submit">Valider</b-button>
      <b-button class="mt-2 form-button-reset" variant="outline-danger" block type="reset">Reset</b-button>
    </b-modal>
  </div>
</template>




<script>
import VueTrix from "vue-trix";
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
        console.log(News[i].category)
        CategoryArray.push(News[i].category)
      }
      const uniqueCaterogy = Array.from(new Set(CategoryArray))
      console.log(uniqueCaterogy)
      return uniqueCaterogy
    },
  },
  methods: {

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

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.add-news {
  position: absolute;
  border-color: black;
  border: 2px solid;
  max-width: 20%;
  transition: left 0.3s ease, right 0.3s ease;
}
.form-button-valid {
  width: 50%;
  margin-left: auto;
  margin-right: auto;
}
.form-button-reset {
  width: 30%;
  margin-left: auto;
  margin-right: auto;
}
</style>