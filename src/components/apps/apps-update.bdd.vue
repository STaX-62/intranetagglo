<template>
  <div>
    <b-icon icon="pencil-square" @click="appsmodal = !appsmodal"></b-icon>
    <b-modal
      id="appmodal1"
      size="xl"
      v-model="appsmodal"
      hide-footer
      style="justify-content:center"
    >
      <template #modal-title>Modification des raccourcis d'application :</template>
      <b-table-simple>
        <b-thead>
          <b-tr>
            <b-th>Titre</b-th>
            <b-th>Lien</b-th>
            <b-th>Icon</b-th>
            <b-th>groups</b-th>
            <b-th></b-th>
            <b-th></b-th>
          </b-tr>
        </b-thead>
        <b-tbody>
          <b-tr v-for="(app,index) in UpdatedApps" :key="index">
            <b-th>{{app.title}}</b-th>
            <b-td>{{app.link}}</b-td>
            <b-td>{{app.icon}}</b-td>
            <b-td>{{app.groups}}</b-td>
            <b-td>
              <button type="button" class="apps-update-button" @click="Modify(app,index)">
                <b-icon class="sidebar-item-icon" variant="info" icon="pencil-square" />
              </button>
            </b-td>
            <b-td>
              <button type="button" class="apps-del-button" @click="DeleteVerification(app,index)">
                <b-icon class="sidebar-item-icon" variant="danger" icon="trash" />
              </button>
            </b-td>
          </b-tr>
        </b-tbody>
      </b-table-simple>
      <div style="width:100%;display:flex">
        <button class="apps-add" @click="Create()">+</button>
      </div>
    </b-modal>

    <b-modal id="appmodal2" size="xl" v-model="updateapp" @ok="Save">
      <template #modal-title>Modification de l'application :</template>
      <div class="menu-form">
        <div>
          <label for="title">Titre</label>
          <b-form-input name="title" type="text" v-model="apptoupdate.title" required />
        </div>
        <div>
          <label for="link">Lien URL</label>
          <b-form-input name="link" type="text" v-model="apptoupdate.link" required />
        </div>
        <div>
          <label for="icon">icon</label>
          <b-form-input name="icon" type="text" v-model="apptoupdate.icon" required />
        </div>
        <label for="groups">Groupes</label>
        <b-form-tags
          id="tags-component-select"
          name="groups"
          v-model="apptoupdate.groups"
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
import { generateUrl, generateOcsUrl } from '@nextcloud/router'

export default {
  name: 'AppsUpdate',
  computed: {
    UpdatedApps() {
      return this.apps;
    },
    availableOptions() {
      return this.options.filter(opt => this.apptoupdate.groups.indexOf(opt) === -1)
    }
  },
  mounted() {
    var url = `apps/intranetagglo${'/apps'}`
    axios.get(generateUrl(url))
      .then(response => (this.apps = response.data))
    axios.get(generateOcsUrl(`cloud/groups`, 2))
      .then(response => (this.options = response.data.ocs.data.groups))
  },
  methods: {
    Save() {
      if (this.apptoupdate.id == null) {
        this.apptoupdate.groups = this.apptoupdate.groups.join(';')
        this.apps.push(this.apptoupdate)
        this.createApps(this.apps[this.arrayindex])
        console.log(this.apps)
      }
      else {
        this.apptoupdate.groups = this.apptoupdate.groups.join(';')
        this.apps[this.arrayindex].title = this.apptoupdate.title;
        this.apps[this.arrayindex].icon = this.apptoupdate.icon;
        this.apps[this.arrayindex].link = this.apptoupdate.link;
        this.apps[this.arrayindex].groups = this.apptoupdate.groups;
        this.updateApps(this.apps[this.arrayindex])
      }
    },
    DeleteVerification(app, index) {
      this.$bvModal.msgBoxConfirm(`Êtes-vous sûr de vouloir supprimer cette application : ${app.title}`, {
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
            this.apps.splice(index, 1)
            this.deleteApps(app.id)
          }
        })
    },
    Create() {
      this.arrayindex = this.apps.length;
      this.apptoupdate.id = null;
      this.apptoupdate.title = "";
      this.apptoupdate.link = "";
      this.apptoupdate.icon = "";
      this.apptoupdate.groups = [];
      this.updateapp = !this.updateapp;
    },
    Modify(apps, index) {
      this.arrayindex = index;
      this.apptoupdate.id = apps.id;
      this.apptoupdate.title = apps.title;
      this.apptoupdate.link = apps.link;
      this.apptoupdate.icon = apps.icon;
      this.apptoupdate.groups = apps.groups.split(';');

      this.updateapp = !this.updateapp;
    },
    async createApps(apps) {
      try {
        var url = `apps/intranetagglo/apps`
        const response = await axios.post(generateUrl(url), apps, { type: 'application/json' })
        this.LastModifiedID = response.data.id
      } catch (e) {
        console.error(e)
      }
      this.$store.commit('setAppsUpdating', true)
    },
    async updateApps(apps) {
      try {
        var url = `apps/intranetagglo/apps/${apps.id}`
        const response = await axios.post(generateUrl(url), apps, { type: 'application/json' })
        this.LastModifiedID = response.data.id
      } catch (e) {
        console.error(e)
      }
      this.$store.commit('setAppsUpdating', false)
    },
    async deleteApps(id) {

      try {
        var url = `apps/intranetagglo/apps/${id}`
        const response = await axios.delete(generateUrl(url, { id }))
        this.LastModifiedID = response.data.id
      } catch (e) {
        console.error(e)
      }
      this.$store.commit('setAppsUpdating', false)
    },
  },
  data: function () {
    return {
      LastModifiedID: null,
      appsmodal: false,
      updateapp: false,
      apps: [],
      options: [],
      arrayindex: null,
      apptoupdate: {
        id: null,
        title: "",
        link: "",
        icon: "",
        groups: []
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