<template>
  <div>
    <button type="button" class="news-update-button" @click="modal = !modal">
      <b-icon class="sidebar-item-icon" variant="info" icon="pencil-square" />
    </button>
    <b-modal size="xl" v-model="modal" ref="modal" hide-footer>
      <template #modal-title>
        Voulez-vous vraiment supprimer
        <code style="font-size: 1.25rem;">{{ newstitle }}</code>
      </template>
      <b-button class="mt-2 form-button-valid" variant="success" block @click="DeleteNews">Valider</b-button>
      <b-button
        class="mt-2 form-button-reset"
        variant="outline-danger"
        block
        @click="hideModal"
      >Retour</b-button>
    </b-modal>
  </div>
</template>

<script>
export default {
  name: 'NewsUpdate',
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
    DeleteNews() {

    }
  },
  props: {
    newstitle: String,
    DelID: Number,
  },
  data: function () {
    return {
      modal: false,
    }
  }
}
</script>
