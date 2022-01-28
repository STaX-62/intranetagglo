<template>
  <div>
    <b-icon icon="pencil-square" @click="appsmodal = !appsmodal"></b-icon>
    <b-modal size="xl" v-model="appsmodal" hide-footer style="justify-content:center">
      <template #modal-title>Modification des raccourcis d'application :</template>
      <b-table-simple>
        <b-thead>
          <b-tr>
            <b-th>Ordre</b-th>
            <b-th>Titre</b-th>
            <b-th>Lien</b-th>
            <b-th>Icon</b-th>
            <b-th>groups</b-th>
            <b-th></b-th>
            <b-th></b-th>
          </b-tr>
        </b-thead>
        <b-tbody>
          <b-tr v-for="(app,index) in apps" :key="index">
            <b-th >{{app.order}}</b-th>
            <b-th>{{app.title}}</b-th>
            <b-td>{{app.link}}</b-td>
            <b-td>{{app.icon}}</b-td>
            <b-td>{{app.groups}}</b-td>
            <b-td>
              <button type="button" class="apps-update-button" @click="Modify(app)">
                <b-icon class="sidebar-item-icon" variant="info" icon="pencil-square" />
              </button>
            </b-td>
            <b-td>
              <button type="button" class="apps-del-button" @click="DeleteVerification(app)">
                <b-icon class="sidebar-item-icon" variant="danger" icon="trash" />
              </button>
            </b-td>
          </b-tr>
        </b-tbody>
      </b-table-simple>
      <div style="width:100%;display:flex">
        <button class="apps-add" @click="AddApps(apps)">+</button>
      </div>
    </b-modal>

    <b-modal size="xl" v-model="updateapp" hide-footer>
      <template #modal-title>Modification de l'application :</template>
      <div class="menu-form">
        <div>
          <label for="title">Titre</label>
          <b-form-input name="title" type="text" v-model="apptoupdate.title" />
        </div>
        <div>
          <label for="link">Lien URL</label>
          <b-form-input name="link" type="text" v-model="apptoupdate.link" />
        </div>
        <div>
          <label for="icon">icon (only Section)</label>
          <b-form-input name="icon" type="text" v-model="apptoupdate.icon" />
        </div>
        <label for="groups">Groupes</label>
        <b-form-tags
          id="tags-component-select"
          name="groups"
          v-model="groups"
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
      </div>
    </b-modal>
  </div>
</template>

<script>
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'

export default {
  name: 'AppsUpdate',
  props: {
  },
  computed: {
    availableOptions() {
      return this.options.filter(opt => this.groups.indexOf(opt) === -1)
    }
  },
  mounted() {
    var url = `apps/intranetagglo${'/apps'}`
    axios.get(generateUrl(url))
      .then(response => (this.apps = response.data))
  },
  methods: {
    AddApps(apps) {
      this.apps.push({
        'order': apps.length + 1,
        'title': 'Nouvelle Section',
        'icon': 'exclamation-triangle',
        'link': '',
        'groups': ''
      })
    },
    DeleteVerification(apps) {
      this.$bvModal.msgBoxConfirm(`Êtes-vous sûr de vouloir supprimer ce menu : ${apps.title}`, {
        title: 'Confirmation',
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
            this.deleteMenu(apps.id)
          }
        })
    },
    Modify(apps) {
      this.apptoupdate.order = apps.order;
      this.apptoupdate.title = apps.title;
      this.apptoupdate.link = apps.link;
      this.apptoupdate.icon = apps.icon;
      this.apptoupdate.groups = apps.groups.split(';');

      this.updateapp = !this.updateapp;
    },
  },
  data: function () {
    return {
      appsmodal: false,
      updateapp: false,
      apps: [],
      groups: [],
      options: ['admin', 'info', 'notme'],
      apptoupdate: {
        order: null,
        title: "",
        link: "",
        icon: "",
        groups: ""
      },
    }
  }
}
</script>
<style scoped>
.apps-add {
  font-size: 19px;
  height: auto;
  margin: auto;
  border-radius: 5px;
  width: 100%;
}
.apps-update-button,
.apps-del-button {
  position: relative;
  font-size: 1.25rem !important;
  width: max-content;
  height: max-content;
  text-align: center;
  vertical-align: middle;
  user-select: none;
  background-color: transparent;
  border: 0;
  color: #343a40;
  border-color: #343a40;
}
</style>