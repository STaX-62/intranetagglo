<template>
    <v-dialog v-model="dialog" max-width="1000">
        <v-card>
            <v-card-title class="text-h5">
                Modification des Applications
                <v-spacer></v-spacer>
                <v-btn fab small @click="openDialog = 5; appToUpdate = EmptyApp">
                    <v-icon>mdi-plus</v-icon>
                </v-btn>
            </v-card-title>

            <v-card-text>
                <v-simple-table>
                    <template v-slot:default>
                        <thead>
                            <tr>
                                <th class="text-left">
                                    App
                                </th>
                                <th class="text-left">
                                    Icone
                                </th>
                                <th class="text-left">
                                    Couleur
                                </th>
                                <th class="text-left">
                                    Lien
                                </th>
                                <th class="text-left">
                                    Groupes d'utilisateurs
                                </th>
                                <th class="text-right">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <draggable tag="tbody" :list="apps" handle=".handleapp" :move="setNewPosition" @end="UpdateOrder">
                            <tr v-for="app in apps" :key="app.name">
                                <td>{{ app.title }}</td>
                                <td>{{ app.icon }}</td>
                                <td>{{ appcolor(app.color) }}</td>
                                <td class="text-truncate" style="max-width: 500px;">{{ app.link }}</td>
                                <td>
                                    <v-chip small v-for="group in app.groups" :key="group">{{ group }}</v-chip>
                                </td>
                                <td class="text-right">
                                    <v-btn icon class="handleapp">
                                        <v-icon>
                                            mdi-arrow-split-horizontal
                                        </v-icon>
                                    </v-btn>
                                    <admin-menu menuType="app" @open="openDialog = $event; appToUpdate = app"></admin-menu>
                                </td>
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
        </v-card>
        <apps-modify :open="openDialog == 0 || openDialog == 5" :app="appToUpdate" :create="openDialog == 5" @close="openDialog = -1" @created="prepare_add" @updated="prepare_update"></apps-modify>
        <admin-change :open="openDialog == 1" @close="openDialog = -1" :title="'Supprimer cette Application'"
            :msg="'Voulez vous vraiment supprimer cette application: <br/><code>' + appToUpdate.title + '</code>'" validate="Supprimer" color="red darken-1" @changed="prepare_delete">
        </admin-change>
    </v-dialog>
</template>

<script>
import adminMenu from './adminMenu.vue'
import AppsModify from './appsModify.vue';
import AdminChange from './adminChange.vue';
import axios from '@nextcloud/axios';
import { generateUrl } from '@nextcloud/router';
import draggable from 'vuedraggable';

export default {
    components: { adminMenu, AppsModify, AdminChange, draggable },
    name: "appsDialog",
    props: {
        open: Boolean,
        create: Boolean
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
    methods: {
        getAdmApps() {
            axios.get(generateUrl(`apps/intranetagglo/apps`))
                .then((response) => {
                    var array = response.data
                    array.forEach(a => {
                        if (a.groups != '') {
                            a.groups = a.groups.split(';')
                        }
                        else {
                            a.groups = []
                        }
                        if (a.icon.indexOf('#') == -1)
                            a.color = a.icon.slice(a.icon.length - 2)
                        else
                            a.color = a.icon.slice(a.icon.length - 2) == "#b" ? 'bleu' : 'vert'
                    })
                    this.apps = array
                })
        },
        prepare_add(app, color) {
            app.groups = app.groups.join(';')
            app.icon = app.icon.trim() + color
            this.createApps(app)
            this.appToUpdate = this.EmptyApp
        },
        prepare_update(app, color) {
            app.groups = app.groups.join(';')
            app.icon = app.icon.trim() + color
            this.updateApps(app)
            this.appToUpdate = this.EmptyApp
        },
        prepare_delete() {
            this.deleteApps()
        },
        async createApps(app) {
            await axios.post(generateUrl(`apps/intranetagglo/apps`), app, { type: 'application/json' }).then(() => {
                this.getAdmApps()
                this.$emit('changed')
            })
        },
        async updateApps(app) {
            await axios.post(generateUrl(`apps/intranetagglo/apps/${app.id}`), app, { type: 'application/json' }).then(() => {
                this.getAdmApps()
                this.$emit('changed')
            })

        },
        async changeOrder(appmoved, appswitched) {
            await axios.post(generateUrl(`apps/intranetagglo/apps/${appmoved.id}`), appmoved, { type: 'application/json' })
            await axios.post(generateUrl(`apps/intranetagglo/apps/${appswitched.id}`), appswitched, { type: 'application/json' })
        },
        async deleteApps() {
            await axios.delete(generateUrl(`apps/intranetagglo/apps/${this.appToUpdate.id}`)).then(() => {
                this.getAdmApps()
                this.$emit('changed')
            })
        },
        appcolor(color) {
            if (color == '#b') {
                return 'bleu'
            }
            if (color == '#v') {
                return 'vert'
            }
        }
    },
    mounted() {
        this.getAdmApps()
    },
    data: () => ({
        apps: [],
        appToUpdate: {
            title: '',
            icon: '',
            link: '',
            groups: []
        },
        EmptyApp: {
            id: 0,
            title: '',
            icon: '',
            link: '',
            groups: []
        },
        newposition: {},
        openDialog: -1
    }),
}
</script>

<style>
</style>