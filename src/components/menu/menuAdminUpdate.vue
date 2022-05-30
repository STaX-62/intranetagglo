<template>
  <b-modal id="menumodal2" size="xl" v-model="detailed" ref="modal" @ok="Save">
    <template #modal-title>Modification du Menu de navigation</template>
    <div class="menu-form">
      <div>
        <label for="title">Titre</label>
        <b-form-input name="title" type="text" v-model="newMenu.title" />
      </div>
      <div v-if="newMenu.haschild != true && !redirectToFile">
        <label for="link">Lien URL</label>
        <b-form-input name="link" type="text" v-model="newMenu.link" />
        <button @click="redirectToFile = !redirectToFile">Rediriger vers un fichier</button>
      </div>
      <div v-if="newMenu.haschild != true && redirectToFile">
        <label for="file">Redirection vers Un fichier (Remplace le lien)</label>
        <b-form-file
          name="file"
          size="sm"
          placeholder="Choisir le fichier..."
          drop-placeholder="Placer l'image ici ..."
          v-model="newMenu.file"
        ></b-form-file>
        <button @click="redirectToFile = !redirectToFile">Rediriger vers une URL</button>
      </div>
      <div v-if="newMenu.icon != null">
        <label for="icon">icon</label>
        <b-form-input name="icon" type="text" v-model="newMenu.icon" />
      </div>
      <label for="groups">Groupes</label>
      <b-form-tags
        id="tags-component-select"
        name="groups"
        v-model="newMenu.groups"
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
</template>

<script>
/* eslint-disable */
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'
import FormData from 'form-data'

export default {
  name: 'menuAdminOverview',
  props: {
    menu: Object,
    active: Boolean
  },
  watch: {
    menu: {
      handler(val) {
        this.newMenu = this.menu
        this.detailed = !this.detailed
      },
      deep: true
    },
    detailed: {
      handler(val) {
        if (!val)
          this.$emit('close');
      }
    }
  },
  computed: {
    availableOptions() {
      return this.$store.state.groupsoptions.filter(opt => this.newMenu.groups.indexOf(opt) === -1)
    },
    MenuToDisplay() {
      var sectionArray = this.menuInBDD[0]
      sectionArray.forEach((section) => {
        var menuArray = this.menuInBDD[1].filter(menu => menu.sectionid == section.sectionid);
        menuArray.forEach((menu) => {
          menu.childs = this.menuInBDD[2].filter(submenu => submenu.menuid == menu.menuid && submenu.sectionid == menu.sectionid);
        })
        section.childs = menuArray;
      })
      return sectionArray
    },
  },
  methods: {
    AddToast(title, subject, variant) {
      this.$bvToast.toast(subject, {
        title: title,
        variant: variant,
        autoHideDelay: 10000,
        appendToast: false
      })
    },
    Save() {
      if (this.newMenu.link == null) {
        this.newMenu = ""
      }
      if (this.newMenu.icon == null) {
        menu.icon = ""
      }
      this.newMenu.groups = this.newMenu.groups.join(';')

      this.updateMenu(this.newMenu, this.newMenu.file)
    },
    async updateMenu(menu, newfile) {
      let data = new FormData();
      data.append('id', menu.id)
      data.append('title', menu.title);
      data.append('link', menu.link);
      data.append('icon', menu.icon);
      data.append('groups', menu.groups);
      if (newfile != null && this.redirectToFile) {
        data.append('file_upd', newfile, newfile.name);
      }
      await axios.post(generateUrl(`apps/intranetagglo/menus/${menu.id}`), data, {
        headers: {
          'accept': 'application/json',
          'Content-Type': `multipart/form-data; boundary=${data._boundary}`,
        }
      }).then((response) => {
        this.$emit('navigation-updated', response.data)
        this.AddToast('Modification de menu', `Le menu '${menu.title.length > 60 ? menu.title.substring(0, 60) + '...' : menu.title}' a bien été modifié`, 'success')
      }).catch((error) => {
        this.AddToast('Erreur durant la modification du menu', error.message, 'danger')
      })
    },
  },
  data: function () {
    return {
      newMenu: {
        title: "",
        link: "",
        file: null,
        icon: null,
        groups: "",
        haschild: false
      },
      redirectToFile: false,
      detailed: false
    }
  }
}
</script>