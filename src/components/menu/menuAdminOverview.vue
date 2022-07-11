<template>
  <div>
    <div class="admin-settings" @click="global = !global">
      <b-icon class="doc-icon" icon="gear"></b-icon>
      <div>Modifier le menu</div>
    </div>
    <b-modal id="menumodal1" size="xl" v-model="global" ref="modal" @ok="UpdateBackground">
      <template #modal-title>Modification du Menu de navigation</template>
      <div style="display:block;position:relative;height:fit-content">
        <div class="menu-table">
          <div class="table-header">Section</div>
          <div class="table-header">Menu</div>
          <div class="table-header">Sous-Menu</div>
          <draggable
            class="table-content"
            tag="div"
            :list="MenuToDisplay"
            draggable=".table-section"
            group="section"
            handle=".handlesec"
            :move="setNewPosition"
            @end="UpdateOrder"
          >
            <div
              class="table-section"
              v-for="(section,Sindex) in MenuToDisplay"
              v-bind:key="section.id"
              v-bind:position="section.sectionid+'-0-0'"
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
                <b-icon icon="arrows-expand" class="handlesec"></b-icon>
              </div>
              <draggable
                class="table-content"
                tag="div"
                :list="MenuToDisplay[Sindex].childs"
                draggable=".table-menu"
                group="menu"
                handle=".handlemen"
                :move="setNewPosition"
                @end="UpdateOrder"
                :sectionid="section.sectionid"
              >
                <div
                  class="table-menu"
                  v-for="(menu,Mindex) in MenuToDisplay[Sindex].childs"
                  v-bind:key="menu.id"
                  v-bind:position="menu.sectionid+'-'+ menu.menuid + '-0'"
                >
                  <div class="table-block" type="text">
                    <div>{{menu.title}}</div>
                    <button type="button" class="menu-update-button" @click="Modify(menu)">
                      <b-icon class="sidebar-item-icon" variant="info" icon="pencil-square" />
                    </button>
                    <button type="button" class="menu-del-button" @click="DeleteVerification(menu)">
                      <b-icon class="sidebar-item-icon" variant="danger" icon="trash" />
                    </button>
                    <b-icon icon="arrows-expand" class="handlemen"></b-icon>
                  </div>
                  <draggable
                    class="table-content"
                    tag="div"
                    :list="MenuToDisplay[Sindex].childs[Mindex].childs"
                    draggable=".table-submenu-content"
                    group="submenu"
                    handle=".handlesub"
                    :move="setNewPosition"
                    @end="UpdateOrder"
                    :sectionid="menu.sectionid"
                    :menuid="menu.menuid"
                  >
                    <div
                      class="table-submenu-content"
                      v-for="submenu in MenuToDisplay[Sindex].childs[Mindex].childs"
                      v-bind:key="submenu.id"
                      v-bind:position="submenu.sectionid+'-'+ submenu.menuid+ '-'+ submenu.submenuid"
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
                        <b-icon icon="arrows-expand" class="handlesub"></b-icon>
                      </div>
                    </div>
                    <button
                      class="menu-add"
                      @click="AddSubmenu(section.sectionid,menu.menuid)"
                      style="width:calc(100% - 10px)"
                    >+</button>
                  </draggable>
                </div>
                <button
                  class="menu-add"
                  @click="AddMenu(section.sectionid)"
                  style="width:calc(50% - 10px)"
                >+</button>
              </draggable>
            </div>
            <button class="menu-add" @click="AddSection()" style="width:calc(33% - 10px)">+</button>
          </draggable>
        </div>
      </div>
    </b-modal>
    <menu-admin-update
      :active="active"
      :autocomplete="modifying"
      @close="active = !active"
      @navigation-updated="UpdateNavigation"
    />
  </div>
</template>

<script>
/* eslint-disable */
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'
import FormData from 'form-data'
import draggable from 'vuedraggable'
import MenuAdminUpdate from './menuAdminUpdate'

export default {
  name: 'MenuAdminOverview',
  components: {
    draggable,
    MenuAdminUpdate
  },
  computed: {
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
    UpdateNavigation(menus) {
      this.menuInBDD = menus;
    },
    UpdateBackground() {
      this.$store.commit('setMenuUpdating', true)
    },
    AddToast(title, subject, variant) {
      this.$bvToast.toast(subject, {
        title: title,
        variant: variant,
        autoHideDelay: 10000,
        appendToast: false
      })
    },
    UpdateOrder() {
      var event = this.newposition;
      if (event.relatedContext.component.$attrs.menuid != null) {

        this.changeOrder(event.dragged.getAttribute("position"), event.related.getAttribute("position"), event.relatedContext.component.$attrs.sectionid, event.relatedContext.component.$attrs.menuid)
      }
      else {
        if (event.relatedContext.component.$attrs.sectionid != null) {
          this.changeOrder(event.dragged.getAttribute("position"), event.related.getAttribute("position"), event.relatedContext.component.$attrs.sectionid, null)
        }
        else {
          this.changeOrder(event.dragged.getAttribute("position"), event.related.getAttribute("position"), null, null)
        }
      }
      this.$forceUpdate()
    },
    setNewPosition(event) {
      this.newposition = event;
      this.$forceUpdate()
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
            this.deleteMenu(menu.id, menu.title)
          }
        })
        .catch(err => {
          // An error occurred
        })
    },
    Modify(menu) {
      this.modifying = menu;
      if (typeof menu.childs !== 'undefined') {
        if (menu.childs.length == 0) this.modifying.haschild = false;
        else this.modifying.haschild = true;
      }
      else {
        this.modifying.haschild = false;
      }
      if (menu.menuid != 0) {
        this.modifying.icon = null;
      }
      this.active = !this.active
    },
    AddSubmenu(Sindex, Mindex) {
      this.createMenu({
        'title': 'Nouveau Sous-Menu',
        'icon': '',
        'link': '',
        'groups': 'admin',
        'sectionid': Sindex,
        'menuid': Mindex,
        'level': 2
      })
    },
    AddMenu(Sindex) {
      this.createMenu({
        'title': 'Nouveau Menu',
        'icon': '',
        'link': '',
        'groups': 'admin',
        'sectionid': Sindex,
        'menuid': 0,
        'level': 1
      })
    },
    AddSection() {
      this.createMenu({
        'title': 'Nouvelle Section',
        'icon': 'exclamation-triangle',
        'link': '',
        'groups': 'admin',
        'sectionid': 0,
        'menuid': 0,
        'level': 0
      })
    },
    async createMenu(menu) {
      let data = new FormData();
      data.append('title', menu.title);
      data.append('link', menu.link);
      data.append('icon', menu.icon);
      data.append('groups', menu.groups);
      data.append('sectionid', menu.sectionid);
      data.append('menuid', menu.menuid);
      data.append('level', menu.level);
      await axios.post(generateUrl(`apps/intranetagglo/menus`), data, {
        headers: {
          'accept': 'application/json',
          'Content-Type': `multipart/form-data; boundary=${data._boundary}`,
        }
      }).then((response) => {
        this.menuInBDD = response.data;
        this.AddToast('Création de menu', `Le menu '${menu.title.length > 60 ? menu.title.substring(0, 60) + '...' : menu.title}' a bien été créé`, 'success')
      }).catch((error) => {
        this.AddToast('Erreur durant la création du menu', error.message, 'danger')
      })
    },
    async deleteMenu(id, title) {
      var url = `apps/intranetagglo/menus/${id}`
      await axios.delete(generateUrl(url, { id })).then((response) => {
        this.menuInBDD = response.data;
        this.AddToast('Suppression de menu', `Le menu '${title.length > 60 ? title.substring(0, 60) + '...' : title}' a bien été supprimé`, 'success')
        this.$forceUpdate()
      }).catch((error) => {
        this.AddToast('Erreur durant la suppression du menu', error.message, 'danger')
      })
    },
    async changeOrder(actualPosition, newPosition, sectionpos, menupos) {
      let data = new FormData();
      data.append('actualPosition', actualPosition);
      data.append('newPosition', newPosition);
      data.append('sectionpos', sectionpos);
      data.append('menupos', menupos);
      await axios.post(generateUrl(`apps/intranetagglo/menus/order`), data, { type: 'application/json' }).then((response) => {
        this.menuInBDD = response.data;
        this.AddToast('Réorganisation des menus', `L'ordre a bien été modifié`, 'success')
        this.$forceUpdate()
      }).catch((error) => {
        this.AddToast('Erreur durant la réorganisation des menus', error.message, 'danger')
      })
    },
  },
  data: function () {
    return {
      global: false,
      updating: false,
      menuInBDD: [[], [], []],
      active: false,
      modifying: {
        title: "",
        link: "",
        file: null,
        icon: null,
        groups: "",
        haschild: false
      },
      newposition: {}
    }
  },
  created: function () {
    var url = `apps/intranetagglo${'/menus'}`
    axios.get(generateUrl(url))
      .then((response) => {
        this.menuInBDD = response.data
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

.handlesec,
.handlemen,
.handlesub {
  margin: auto;
}
</style>