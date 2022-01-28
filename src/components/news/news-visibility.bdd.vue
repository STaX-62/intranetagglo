<template>
  <div>
    <button type="button" class="news-visibility-button" @click="modal = !modal">
      <b-icon class="sidebar-item-icon" variant="dark" icon="eye" v-if="visibility" />
      <b-icon class="sidebar-item-icon" variant="dark" icon="eye-slash" v-else />
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
  name: 'NewsVisibility',
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
    visibility: Boolean,
    newstitle: String,
  },
  data: function () {
    return {
      modal: false,
    }
  }
}
</script>