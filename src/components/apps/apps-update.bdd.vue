<template>
  <b-modal size="xl" v-model="modal" hide-footer>
    <template #modal-title>
      Modification de l'application :
      <code style="font-size: 1.25rem;">{{apptoupdate.title}}</code>
    </template>
    <form ref="form" @submit.stop.prevent="handleSubmit">
      <b-table-simple hover small caption-top stacked>
        <caption>Modifier ici les informations de l'application</caption>
        <b-tbody>
          <b-tr>
            <b-th>
              <b-form-group
                label="Titre"
                label-for="titre-input"
                invalid-feedback="Un titre est requis"
              >
                <b-form-input id="titre-input" v-model="apptoupdate.title"></b-form-input>
              </b-form-group>
            </b-th>
          </b-tr>
          <b-tr>
            <b-th>
              <b-form-group
                label="Icone"
                label-for="icon-input"
                invalid-feedback="Une Icone est requise"
              >
                <b-form-input id="icon-input" v-model="apptoupdate.icon"></b-form-input>
              </b-form-group>
            </b-th>
          </b-tr>
          <b-tr>
            <b-th>
              <b-form-group
                label="Lien de redirection"
                label-for="link-input"
                invalid-feedback="Un lien de redirection est requis"
              >
                <b-form-input id="link-input" v-model="apptoupdate.link"></b-form-input>
              </b-form-group>
            </b-th>
          </b-tr>
        </b-tbody>
      </b-table-simple>
      <b-row class="mt-2">
        <b-col md="4" offset-md="4">
          <b-button variant="outline-success" block>Valider</b-button>
        </b-col>
      </b-row>
    </form>
  </b-modal>
</template>

<script>
export default {
  name: 'AppsUpdate',
  props: {
    apptoupdate: Object,
  },
  computed: {
  },
  methods: {
  },
  watch: {
    modalchange: function (modal) {
      console.log(modal)
    }
  },
  data: function () {
    return {
      modal: false,
    }
  }
}
</script>