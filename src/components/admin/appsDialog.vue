<template>
    <v-dialog v-model="dialog" max-width="1000">
        <v-card>
            <v-card-title class="text-h5">
                Modification des Applications
                <v-spacer></v-spacer>
                <v-btn fab small @click="openDialog = 5; appToUpdate = appEmpty">
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
                        <tbody>
                            <tr v-for="app in apps" :key="app.name">
                                <td>{{ app.title }}</td>
                                <td class="text-truncate" style="max-width: 500px;">{{ app.link }}</td>
                                <td>
                                    <v-chip small v-for="group in app.groups" :key="group">{{ group }}</v-chip>
                                </td>
                                <td class="text-right">
                                    <admin-menu menuType="app" @open="openDialog = $event; appToUpdate = app"></admin-menu>
                                </td>
                            </tr>
                        </tbody>
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
        <apps-modify :open="openDialog == 0 || openDialog == 5" :app="appToUpdate" :create="openDialog == 5" @close="openDialog = -1"></apps-modify>
        <admin-change :open="openDialog == 1" @close="openDialog = -1" :title="'Supprimer cette Application'"
            :msg="'Voulez vous vraiment supprimer cette application: <br/><code>' + appToUpdate.title + '</code>'" validate="Supprimer" color="red darken-1">
        </admin-change>
    </v-dialog>
</template>

<script>
import adminMenu from './adminMenu.vue'
import AppsModify from './appsModify.vue';
import AdminChange from './adminChange.vue';
export default {
    components: { adminMenu, AppsModify, AdminChange },
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
    data: () => ({
        apps: [{
            id: 1,
            title: 'Annuaire',
            icon: 'mdi-phone',
            link: 'https://vuetifyjs.com/en/components/simple-tables/',
            groups: ['intranet-admin', 'admin']
        }],
        appToUpdate: {
            id: 0,
            title: '',
            icon: '',
            link: '',
            groups: []
        },
        appEmpty: {
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