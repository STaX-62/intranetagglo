<template>
    <v-dialog v-model="dialog" max-width="1500">
        <v-card>
            <v-card-title class="text-h5">
                Modification des Menus de Navigation
            </v-card-title>

            <v-card-text>

                <v-simple-table dense fixed-header height="700">
                    <template v-slot:default>
                        <thead>
                            <tr>
                                <th class="text-left">
                                    Sections
                                </th>
                                <th class="text-left">
                                    Menus
                                </th>
                            </tr>
                        </thead>
                        <draggable tag="tbody" :list="menus" handle=".handlesec" draggable=".intra-section" group="section" :move="setNewPosition" @end="UpdateOrder">
                            <tr class="intra-section" v-for="(section, Sindex) in MenuToDisplay" v-bind:key="section.id" v-bind:position="section.sectionid + '-0-0'">
                                <td>
                                    <v-card class="d-flex align-center" style="height:calc(100% - 4px)" outlined>
                                        <v-card-subtitle style="font-size: 15px;">{{ section.title }}</v-card-subtitle>
                                        <v-spacer></v-spacer>
                                        <v-card-actions>
                                            <v-btn icon class="handlesec">
                                                <v-icon>
                                                    mdi-arrow-split-horizontal
                                                </v-icon>
                                            </v-btn>
                                            <admin-menu menuType="nav" @open=" menuToUpdate = section; menuToUpdate.childs = section.childs; openDialog = $event"></admin-menu>
                                        </v-card-actions>
                                    </v-card>
                                </td>
                                <draggable tag="td" :list="MenuToDisplay[Sindex].childs" handle=".handlemen" draggable=".intra-menu" group="menus" :move="setNewPosition" @end="UpdateOrder"
                                    :sectionid="section.sectionid">
                                    <v-row v-for="(menu, Mindex) in MenuToDisplay[Sindex].childs" v-bind:key="menu.id" style="margin:0" class="intra-menu"
                                        v-bind:position="menu.sectionid + '-' + menu.menuid + '-0'">
                                        <v-col class="pa-1">
                                            <v-card class="d-flex align-center" style="height:calc(100% - 4px)" outlined>
                                                <v-card-subtitle style="font-size: 15px;">{{ menu.title }}</v-card-subtitle>
                                                <v-spacer></v-spacer>
                                                <v-card-actions>
                                                    <v-btn icon class="handlemen">
                                                        <v-icon>
                                                            mdi-arrow-split-horizontal
                                                        </v-icon>
                                                    </v-btn>
                                                    <admin-menu menuType="nav" @open=" menuToUpdate = menu; menuToUpdate.childs = menu.childs; openDialog = $event"></admin-menu>
                                                </v-card-actions>
                                            </v-card>

                                        </v-col>
                                        <v-col class="pa-1">
                                            <draggable tag="div" :list="MenuToDisplay[Sindex].childs[Mindex].childs" handle=".handlesub" draggable=".intra-submenu" group="submenus"
                                                :move="setNewPosition" @end="UpdateOrder" :sectionid="menu.sectionid" :menuid="menu.menuid">
                                                <v-card class="d-flex mb-1 intra-submenu" v-for="submenu in MenuToDisplay[Sindex].childs[Mindex].childs" v-bind:key="submenu.id" outlined
                                                    v-bind:position="submenu.sectionid + '-' + submenu.menuid + '-' + submenu.submenuid">
                                                    <v-card-subtitle style="font-size: 15px;">{{ submenu.title }}</v-card-subtitle>
                                                    <v-spacer></v-spacer>
                                                    <v-card-actions>
                                                        <v-btn icon class="handlesub">
                                                            <v-icon>
                                                                mdi-arrow-split-horizontal
                                                            </v-icon>
                                                        </v-btn>
                                                        <admin-menu menuType="nav" @open=" menuToUpdate = submenu; menuToUpdate.childs = 0;openDialog = $event"></admin-menu>
                                                    </v-card-actions>
                                                </v-card>
                                                <v-card class="d-flex mb-1" outlined>
                                                    <v-card-actions class="mx-auto">
                                                        <v-btn icon
                                                            @click=" menuToUpdate = EmptyMenu; menuToUpdate.sectionid = section.sectionid; menuToUpdate.menuid = menu.menuid; menuToUpdate.level = 2;openDialog = 5">
                                                            <v-icon>mdi-plus</v-icon>
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </draggable>
                                        </v-col>
                                    </v-row>
                                    <v-card class="d-flex mb-1 mx-1" outlined style="width:calc(50% - 8px)">
                                        <v-card-actions class="mx-auto">
                                            <v-btn icon
                                                @click=" menuToUpdate = EmptyMenu; menuToUpdate.sectionid = section.sectionid; menuToUpdate.menuid = 0; menuToUpdate.level = 1;openDialog = 5">
                                                <v-icon>mdi-plus</v-icon>
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </draggable>
                            </tr>
                            <tr>
                                <td>
                                    <v-card class="d-flex my-1" outlined>
                                        <v-card-actions class="mx-auto">
                                            <v-btn icon @click=" menuToUpdate = EmptyMenu; menuToUpdate.sectionid = 0; menuToUpdate.menuid = 0; menuToUpdate.level = 0 ;openDialog = 5">
                                                <v-icon>mdi-plus</v-icon>
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </td>
                                <td></td>
                            </tr>
                        </draggable>
                    </template>
                </v-simple-table>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn color="grey" text @click="dialog = false">
                    Fermer
                </v-btn>
            </v-card-actions>
            <nav-modify :open="openDialog == 0 || openDialog == 5" :nav="menuToUpdate" :create="openDialog == 5" @close="openDialog = -1" @created="prepare_add" @updated="prepare_update"></nav-modify>
            <admin-change :open="openDialog == 1" @close="openDialog = -1" :title="'Supprimer ce Menu'" :msg="'Voulez vous vraiment supprimer ce menu: <br/><code>' + menuToUpdate.title + '</code>'"
                validate="Supprimer" color="red darken-1" @changed="prepare_delete">
            </admin-change>
        </v-card>
    </v-dialog>
</template>

<script>
import adminMenu from './adminMenu.vue'
import draggable from 'vuedraggable'
import NavModify from './navModify.vue'
import AdminChange from './adminChange.vue'
import axios from '@nextcloud/axios';
import { generateUrl } from '@nextcloud/router';

export default {
    components: { adminMenu, draggable, NavModify, AdminChange },
    name: "navDialog",
    props: {
        open: Boolean
    },
    computed: {
        dialog: {
            get() {
                return this.open
            },
            set() {
                this.$emit('close')
            }
        },
        MenuToDisplay() {
            var sectionArray = this.menus[0]
            sectionArray.forEach((section) => {
                var menuArray = this.menus[1].filter(menu => menu.sectionid == section.sectionid);
                menuArray.forEach((menu) => {
                    menu.childs = this.menus[2].filter(submenu => submenu.menuid == menu.menuid && submenu.sectionid == menu.sectionid);
                })
                section.childs = menuArray;
            })
            return sectionArray
        },
    },
    methods: {
        getAdmMenus() {
            var url = `apps/intranetagglo${'/menus'}`
            axios.get(generateUrl(url))
                .then((response) => {
                    var array = response.data
                    array.forEach(m => {
                        m.forEach(n => {
                            if (n.groups != '') {
                                n.groups = n.groups.split(';')
                            }
                            else {
                                n.groups = []
                            }
                        })
                    })
                    this.menus = array
                })
        },
        setNewPosition(event) {
            this.newposition = event;
            this.$forceUpdate()
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
        prepare_add() {
            this.menuToUpdate.groups = this.menuToUpdate.groups.join(';')
            this.menuToUpdate.icon = this.menuToUpdate.icon.trim()
            this.createMenu(this.menuToUpdate)
        },
        prepare_update() {
            this.menuToUpdate.groups = this.menuToUpdate.groups.join(';')
            this.menuToUpdate.icon = this.menuToUpdate.icon.trim()
            this.updateMenu(this.menuToUpdate)
        },
        prepare_delete() {
            this.deleteMenu()
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
            }).then(() => {
                this.menuToUpdate = this.EmptyMenu
                this.getAdmMenus()
                this.$emit('changed')
            })
        },
        async updateMenu(menu) {
            let data = new FormData();
            data.append('id', menu.id)
            data.append('title', menu.title);
            data.append('link', menu.link);
            data.append('icon', menu.icon);
            data.append('groups', menu.groups);
            await axios.post(generateUrl(`apps/intranetagglo/menus/${menu.id}`), data, {
                headers: {
                    'accept': 'application/json',
                    'Content-Type': `multipart/form-data; boundary=${data._boundary}`,
                }
            }).then(() => {
                console.log(this.menuToUpdate)
                this.menuToUpdate = this.EmptyMenu
                console.log(this.EmptyMenu)
                console.log(this.menuToUpdate)
                this.getAdmMenus()
                this.$emit('changed')
            })
        },
        async deleteMenu() {
            var url = `apps/intranetagglo/menus/${this.menuToUpdate.id}`
            var id = this.menuToUpdate.id
            await axios.delete(generateUrl(url, { id })).then(() => {
                this.getAdmMenus()
                this.$emit('changed')
            })
        },
        async changeOrder(actualPosition, newPosition, sectionpos, menupos) {
            let data = new FormData();
            data.append('actualPosition', actualPosition);
            data.append('newPosition', newPosition);
            data.append('sectionpos', sectionpos);
            data.append('menupos', menupos);
            await axios.post(generateUrl(`apps/intranetagglo/menus/order`), data, { type: 'application/json' }).then(() => {
                this.getAdmMenus()
                this.$emit('changed')
            })
        },
    },
    mounted() {
        this.getAdmMenus()
    },
    data: () => ({
        newposition: {},
        menus: [[], [], []],
        menuToUpdate: {
            id: 0,
            title: '',
            icon: '',
            link: '',
            groups: [],
            sectionid: 0,
            menuid: 0,
            level: 0,
            childs: 0
        },
        EmptyMenu: {
            id: 0,
            title: '',
            icon: '',
            link: '',
            groups: [],
            sectionid: 0,
            menuid: 0,
            level: 0,
            childs: 0
        },
        openDialog: -1
    }),
}
</script>