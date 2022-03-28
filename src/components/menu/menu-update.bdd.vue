<template>
  <div>
    <div class="admin-settings" @click="global = !global">
      <b-icon class="doc-icon" icon="gear"></b-icon>
      <div>Modifier le menu</div>
    </div>
    <b-modal id="menumodal1" size="xl" v-model="global" ref="modal">
      <template #modal-title>Modification du Menu de navigation</template>
      <div style="display:block;position:relative;height:fit-content">
        <div class="menu-table">
          <div class="table-header">Section</div>
          <div class="table-header">Menu</div>
          <div class="table-header">Sous-Menu</div>
          <draggable
            class="table-content"
            tag="div"
            :list="sectionArray"
            draggable=".table-section"
            handle=".handlesec"
            @sort="UpdateOrder"
          >
            <div
              class="table-section"
              v-for="(section,Sindex) in sectionArray"
              v-bind:key="section.id"
              v-bind:position="Sindex+'-0-0'"
            >
              <div class="table-block" type="text">
                {{section.title}}
                <button
                  type="button"
                  class="menu-update-button"
                  @click="Modify(section)"
                >
                  <b-icon class="sidebar-item-icon" variant="info" icon="pencil-square" />
                </button>
                <button type="button" class="menu-del-button" @click="DeleteVerification(section)">
                  <b-icon class="sidebar-item-icon" variant="danger" icon="trash" />
                </button>
                <b-icon icon="arrows-move" class="handlesec"></b-icon>
              </div>
              <draggable
                class="table-content"
                tag="div"
                :list="sectionArray[Sindex].childs"
                draggable=".table-menu"
                handle=".handlemen"
                @sort="UpdateOrder"
              >
                <div
                  class="table-menu"
                  v-for="(menu,Mindex) in sectionArray[Sindex].childs"
                  v-bind:key="menu.id"
                  v-bind:position="Sindex+'-'+ (Mindex+1) + '-0'"
                >
                  <div class="table-block" type="text">
                    <div>{{menu.title}}</div>
                    <button type="button" class="menu-update-button" @click="Modify(menu)">
                      <b-icon class="sidebar-item-icon" variant="info" icon="pencil-square" />
                    </button>
                    <button type="button" class="menu-del-button" @click="DeleteVerification(menu)">
                      <b-icon class="sidebar-item-icon" variant="danger" icon="trash" />
                    </button>
                    <b-icon icon="arrows-move" class="handlemen"></b-icon>
                  </div>
                  <draggable
                    class="table-content"
                    tag="div"
                    :list="sectionArray[Sindex].childs[Mindex].childs"
                    :options="{group: 'submenus-' + Sindex + '-'+ Mindex}"
                    draggable=".table-submenu-content"
                    handle=".handlesub"
                    @sort="UpdateOrder"
                  >
                    <div
                      class="table-submenu-content"
                      v-for="(submenu,SMindex) in sectionArray[Sindex].childs[Mindex].childs"
                      v-bind:key="submenu.id"
                      v-bind:position="Sindex+'-'+ (Mindex+1)+ '-'+ (SMindex+1)"
                    >
                      <div class="table-block" type="text">
                        <div>{{submenu.title}}</div>
                        <button type="button" class="menu-update-button" @click="Modify(submenu)">
                          <b-icon class="sidebar-item-icon" variant="info" icon="pencil-square" />
                        </button>
                        <button
                          type="button"
                          class="menu-del-button"
                          @click="DeleteVerification(submenu)"
                        >
                          <b-icon class="sidebar-item-icon" variant="danger" icon="trash" />
                        </button>
                        <b-icon icon="arrows-move" class="handlesub"></b-icon>
                      </div>
                    </div>
                    <button
                      class="menu-add"
                      @click="AddSubmenu(sectionArray[Sindex].childs[Mindex].childs,Sindex,Mindex)"
                      style="width:calc(100% - 10px)"
                    >+</button>
                  </draggable>
                </div>
                <button
                  class="menu-add"
                  @click="AddMenu(sectionArray[Sindex].childs,Sindex)"
                  style="width:calc(50% - 10px)"
                >+</button>
              </draggable>
            </div>
            <button
              class="menu-add"
              @click="AddSection(sectionArray)"
              style="width:calc(33% - 10px)"
            >+</button>
          </draggable>
        </div>
      </div>
    </b-modal>
    <b-modal id="menumodal2" size="xl" v-model="detailed" ref="modal" @ok="Save">
      <template #modal-title>Modification du Menu de navigation</template>
      <div class="menu-form">
        <div>
          <label for="title">Titre</label>
          <b-form-input name="title" type="text" v-model="modifying.title" />
        </div>
        <div v-if="modifying.haschild != true && !redirectToFile">
          <label for="link">Lien URL</label>
          <b-form-input name="link" type="text" v-model="modifying.link" />
          <button @click="redirectToFile = !redirectToFile">Rediriger vers un fichier</button>
        </div>
        <div v-if="modifying.haschild != true && redirectToFile">
          <label for="file">Redirection vers Un fichier (Remplace le lien)</label>
          <b-form-file
            name="file"
            size="sm"
            placeholder="Choisir le fichier..."
            drop-placeholder="Placer l'image ici ..."
            v-model="modifying.file"
          ></b-form-file>
          <button @click="redirectToFile = !redirectToFile">Rediriger vers une URL</button>
        </div>
        <div v-if="modifying.icon != null">
          <label for="icon">icon (only Section)</label>
          <b-form-input name="icon" type="text" v-model="modifying.icon" />
        </div>
        <label for="groups">Groupes</label>
        <b-form-tags
          id="tags-component-select"
          name="groups"
          v-model="modifying.groups"
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
/* eslint-disable */
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'
import FormData from 'form-data'
import draggable from 'vuedraggable'
import Vue from 'vue'

export default {
  name: 'NewsUpdate',
  components: {
    draggable,
  },
  watch: {
    updating: function (val) {
      if (val) {
        axios.get(generateUrl(`apps/intranetagglo${'/menusG'}`))
          .then((response) => {
            this.sectionArray = response.data[0];
            this.sectionArray.forEach((section) => {
              var menuArray = response.data[1].filter(menu => menu.position.slice(0, 2) == section.position.slice(0, 2))
              menuArray.forEach((menu) => {
                menu.childs = response.data[2].filter(submenu => submenu.position.slice(0, 4) == menu.position.slice(0, 4));
              })
              section.childs = menuArray;
            })
            this.$store.commit('setMenuAdminUpdating', false)
          })
      }
    },
  },
  computed: {
    updating() {
      return this.$store.state.menuAdminUpdating
    },
    availableOptions() {
      return this.$store.state.groupsoptions.filter(opt => this.modifying.groups.indexOf(opt) === -1)
    },
  },
  methods: {
    UpdateOrder: function (event) {
      console.log(event)
      this.$forceUpdate()
      this.changeOrder(event.clone.getAttribute("position"), event.newIndex, event.oldIndex).then(() => {
        this.$store.commit('setMenuAdminUpdating', true)
      })
    },
    DeleteVerification(menu) {
      this.$bvModal.msgBoxConfirm(`Êtes-vous sûr de vouloir supprimer ce menu : ${menu.title}`, {
        title: 'Confirmation',
        id: 'menumodal3',
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
            this.deleteMenu(menu.id).then(() => {
              this.$store.commit('setMenuAdminUpdating', true)
            })
          }
        })
        .catch(err => {
          // An error occurred
        })
    },
    Modify(menu) {
      this.modifying = menu;
      this.modifying.groups = menu.groups.split(';');
      var positions = menu.position.split('-');

      if (positions[1] == 0) {
        this.modifying.icon = menu.icon;
        if (this.menusArray[0].length == 0) this.modifying.haschild = false;
        else this.modifying.haschild = true;
      }
      else {
        if (positions[2] == 0) {
          if (this.submenusArray[positions[0]][positions[1] - 1].length == 0) this.modifying.haschild = false;
          else this.modifying.haschild = true;
        }
        else {
          this.modifying.haschild = false;
        }
      }

      this.detailed = !this.detailed;
    },
    Save() {
      if (this.modifying.link == null) {
        this.modifying = ""
      }
      if (this.modifying.icon == null) {
        menu.icon = ""
      }
      this.modifying.groups = this.modifying.groups.join(';')

      this.updateMenu(this.modifying, this.modifying.file).then(() => {
        this.$store.commit('setMenuAdminUpdating', true)
      })

    },
    AddSubmenu(submenus, Sindex, Mindex) {
      this.createMenu({
        'title': 'Nouveau Sous-Menu',
        'icon': '',
        'link': '',
        'groups': 'admin',
        'position': Sindex + '-' + (Mindex + 1) + '-' + (submenus.length + 1)
      }).then(() => {
        this.$store.commit('setMenuAdminUpdating', true)
      })
    },
    AddMenu(menu, Sindex) {
      this.createMenu({
        'title': 'Nouveau Menu',
        'icon': '',
        'link': '',
        'groups': 'admin',
        'position': Sindex + '-' + (menu.length + 1) + '-0'
      }).then(() => {
        this.$store.commit('setMenuAdminUpdating', true)
      })
    },
    AddSection(section) {
      this.createMenu({
        'title': 'Nouvelle Section',
        'icon': 'exclamation-triangle',
        'link': '',
        'groups': 'admin',
        'position': section.length + '-0-0'
      }).then(() => {
        this.$store.commit('setMenuAdminUpdating', true)
      })
    },
    async createMenu(menu) {
      try {
        let data = new FormData();
        data.append('title', menu.title);
        data.append('link', menu.link);
        data.append('icon', menu.icon);
        data.append('groups', menu.groups);
        data.append('position', menu.position);
        const response = await axios.post(generateUrl(`apps/intranetagglo/menus`), data, {
          headers: {
            'accept': 'application/json',
            'Content-Type': `multipart/form-data; boundary=${data._boundary}`,
          }
        })
        this.LastModifiedID = response.data.id
      } catch (e) {
        console.error(e)
      }
      this.$store.commit('setMenuUpdating', true)
    },
    async updateMenu(menu, newfile) {
      try {

        let data = new FormData();
        data.append('id', menu.id)
        data.append('title', menu.title);
        data.append('link', menu.link);
        data.append('icon', menu.icon);
        data.append('groups', menu.groups);
        if (newfile != null && this.redirectToFile) {
          data.append('file_upd', newfile, newfile.name);
        }
        const response = await axios.post(generateUrl(`apps/intranetagglo/menus/${menu.id}`), data, {
          headers: {
            'accept': 'application/json',
            'Content-Type': `multipart/form-data; boundary=${data._boundary}`,
          }
        })
        this.LastModifiedID = response.data.id
      } catch (e) {
        console.error(e)
      }
    },
    async deleteMenu(id) {
      try {
        var url = `apps/intranetagglo/menus/${id}`
        const response = await axios.delete(generateUrl(url, { id }))
        this.LastModifiedID = response.data.id
      } catch (e) {
        console.error(e)
      }
    },
    async changeOrder(actualPosition, newIndex, oldIndex) {
      try {
        let data = new FormData();
        data.append('oldPosition', actualPosition);
        data.append('newIndex', newIndex);
        data.append('oldIndex', oldIndex);
        await axios.post(generateUrl(`apps/intranetagglo/menus/order`), data, { type: 'application/json' })
      } catch (e) {
        console.error(e)
      }
    },
  },
  data: function () {
    return {
      global: false,
      detailed: false,
      updating: false,
      sectionArray: [],
      tempMenus: [],
      LastModifiedID: null,
      redirectToFile: false
    }
  },
  mounted() {
    var url = `apps/intranetagglo${'/menus'}`
    axios.get(generateUrl(url))
      .then((response) => {
        this.sectionArray = response.data[0];
        this.sectionArray.forEach((section) => {
          var menuArray = response.data[1].filter(menu => menu.position.slice(0, 2) == section.position.slice(0, 2))
          menuArray.forEach((menu) => {
            menu.childs = response.data[2].filter(submenu => submenu.position.slice(0, 4) == menu.position.slice(0, 4));
          })
          section.childs = menuArray;
        })
      })
  },

}
</script>

<style scoped>
.admin-settings {
  display: flex;
  justify-content: center;
  vertical-align: middle;
  color: var(--color-mode-1);
  background-color: var(--color-secondary);
  border: solid 2px;
  border-radius: 0.25rem;
  border-color: var(--color-secondary);
  font-size: 13px;
  cursor: pointer;
}

.admin-settings div {
  margin-top: auto;
  margin-bottom: auto;
}

.admin-settings svg {
  padding: 7px 0 7px 0 !important;
  margin-top: auto;
  margin-bottom: auto;
}

.doc-icon {
  font-size: 2.5em;
  padding: 5px 0 10px 0;
}

.menu-table {
  display: grid;
  grid-template-columns: 33% 33% 33%;
  grid-auto-rows: 50px auto;
  grid-template-areas:
    "MainDiv Div SubDiv"
    "Content Content Content";
}

.table-content {
  grid-area: Content;
  width: 100%;
  height: 100%;
}

.table-content button {
  min-height: 30px;
}

.table-section {
  display: grid;
  grid-template-columns: 33% 66%;
  grid-auto-rows: auto;
  grid-template-areas: ". Content";
  row-gap: 10px;
  width: 100%;
}

.table-block {
  display: grid;
  grid-template-columns: calc(100% - 150px) 50px 50px 50px;
  grid-auto-rows: auto;
  grid-template-areas: ". . .";
  background-color: var(--color-mode-1);
  border-style: solid;
  border-color: var(--color-mode-shadow-1);
  border-width: 1px;
  margin: 5px 5px 5px 5px;
  border-radius: 5px;
  text-align: center;
  align-items: center;
}

.menu-update-button,
.menu-del-button {
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

.table-menu {
  display: grid;
  grid-template-columns: 50% 50%;
  grid-auto-rows: auto;
  grid-template-areas: "Menu Content";
  row-gap: 10px;
}

.table-menu input {
  grid-area: Menu;
}

.table-submenu-content button {
  display: block;
}
.menu-add {
  font-size: 19px;
  height: auto;
  margin: 5px 50px 5px 5px;
  border-radius: 5px;
}

.handle:before {
  content: "\e068";
}
</style>