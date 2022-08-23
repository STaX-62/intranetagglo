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
                        <draggable tag="tbody" :list="nav" handle=".handlesec" group="section">
                            <tr v-for="(item, i) in nav" :key="i">
                                <td>
                                    <v-card class="d-flex align-center" style="height:calc(100% - 4px)" outlined>
                                        <v-card-subtitle>Test Section {{ item.id }}</v-card-subtitle>
                                        <v-spacer></v-spacer>
                                        <v-card-actions>
                                            <v-btn icon class="handlesec">
                                                <v-icon>
                                                    mdi-arrow-split-horizontal
                                                </v-icon>
                                            </v-btn>
                                            <admin-menu menuType="nav" @open="openDialog = $event; appToUpdate = app"></admin-menu>
                                        </v-card-actions>
                                    </v-card>
                                </td>
                                <draggable tag="td" :list="nav" handle=".handlemen" group="menus">
                                    <v-row v-for="item in 2" :key="item" style="margin:0">
                                        <v-col class="pa-1">
                                            <v-card class="d-flex align-center" style="height:calc(100% - 4px)" outlined>
                                                <v-card-subtitle>Test Menus</v-card-subtitle>
                                                <v-spacer></v-spacer>
                                                <v-card-actions>
                                                    <v-btn icon class="handlemen">
                                                        <v-icon>
                                                            mdi-arrow-split-horizontal
                                                        </v-icon>
                                                    </v-btn>
                                                    <admin-menu menuType="nav" @open="openDialog = $event; appToUpdate = app"></admin-menu>
                                                </v-card-actions>
                                            </v-card>

                                        </v-col>
                                        <v-col class="pa-1">
                                            <draggable :list="nav" handle=".handlesub" group="submenus">
                                                <v-card class="d-flex mb-1" v-for="item in 3" :key="item" outlined>
                                                    <v-card-subtitle>Test SubMenu</v-card-subtitle>
                                                    <v-spacer></v-spacer>
                                                    <v-card-actions>
                                                        <v-btn icon class="handlesub">
                                                            <v-icon>
                                                                mdi-arrow-split-horizontal
                                                            </v-icon>
                                                        </v-btn>
                                                        <admin-menu menuType="nav" @open="openDialog = $event; appToUpdate = app"></admin-menu>
                                                    </v-card-actions>
                                                </v-card>
                                                <v-card class="d-flex mb-1" outlined>
                                                    <v-card-actions class="mx-auto">
                                                        <v-btn icon>
                                                            <v-icon>mdi-plus</v-icon>
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </draggable>
                                        </v-col>
                                    </v-row>
                                    <v-card class="d-flex mb-1 mx-1" outlined style="width:calc(50% - 8px)">
                                        <v-card-actions class="mx-auto">
                                            <v-btn icon>
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
                                            <v-btn icon>
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
            <nav-modify :open="openDialog == 0 || openDialog == 5" :nav="navToUpdate" :create="openDialog == 5" @close="openDialog = -1"></nav-modify>
            <admin-change :open="openDialog == 1" @close="openDialog = -1" :title="'Supprimer ce Menu'" :msg="'Voulez vous vraiment supprimer ce menu: <br/><code>' + navToUpdate.title + '</code>'"
                validate="Supprimer" color="red darken-1">
            </admin-change>
        </v-card>
    </v-dialog>
</template>

<script>
import adminMenu from './adminMenu.vue'
import draggable from 'vuedraggable'
import NavModify from './navModify.vue'
import AdminChange from './adminChange.vue'
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
        }
    },
    data: () => ({
        nav: [{
            id: 1,
            title: 'Annuaire',
            icon: 'mdi-phone',
            link: 'https://vuetifyjs.com/en/components/simple-tables/',
            groups: ['intranet-admin', 'admin'],
            sectionid: 0,
            menuid: 0,
            level: 0
        },
        {
            id: 2,
            title: 'Annuaire',
            icon: 'mdi-phone',
            link: 'https://vuetifyjs.com/en/components/simple-tables/',
            groups: ['intranet-admin', 'admin'],
            sectionid: 0,
            menuid: 0,
            level: 0
        }],
        navToUpdate: {
            id: 0,
            title: '',
            icon: '',
            link: '',
            groups: []
        },
        navEmpty: {
            id: 0,
            title: '',
            icon: '',
            link: '',
            groups: []
        },
        openDialog: -1
    }),
}
</script>

<style>
</style>