<template>
    <v-navigation-drawer app right>
        <h2 class="mt-2 text-center">Applications
        </h2>
        <v-row class="ma-1 apps-container" v-if="!apps.length">
            <v-col v-for="index in 4" :key="index" cols="6">
                <v-responsive :aspect-ratio="1 / 1">
                    <v-skeleton-loader class="mx-auto" type="button" height="100%" width="100%"></v-skeleton-loader>
                </v-responsive>
            </v-col>
        </v-row>
        <v-row class="ma-1 apps-container" v-if="apps.length">
            <v-col v-for="(app, index) in apps" :key="index" cols="6">
                <v-responsive :aspect-ratio="1 / 1">
                    <v-btn :class="'appbox ' + appcolor(app.color)" style="height: 100%;width:100%;" :href="app.link" target="_blank">
                        <div class="text-center d-flex flex-column align-center justify-center text-wrap" style="height: 100%; letter-spacing: normal; font-size:smaller;">
                            <v-icon class="pb-1">mdi-{{ app.icon }}</v-icon>
                            {{ app.title }}
                        </div>
                    </v-btn>
                </v-responsive>
            </v-col>
        </v-row>
        <template v-slot:append>
            <div class="d-flex py-3">
                <v-btn text class="mx-auto" color="primary" @click="openDialog = !openDialog">
                    <v-icon class="mr-2">mdi-cog-outline</v-icon>
                    Modifer les Apps
                </v-btn>
            </div>
        </template>
        <apps-dialog :open="openDialog" @close="openDialog = false" @changed="getApps"></apps-dialog>
    </v-navigation-drawer>
</template>

<script>
import appsDialog from './admin/appsDialog.vue'
import axios from '@nextcloud/axios';
import { generateUrl } from '@nextcloud/router';
export default {
    components: { appsDialog },
    name: "Navigation",
    methods: {
        getApps() {
            axios.get(generateUrl(`apps/intranetagglo/appsG`))
                .then((response) => {
                    var array = response.data
                    array.forEach(a => {
                        if (a.groups != '') {
                            a.groups = a.groups.split(';')
                        }
                        else {
                            a.groups = []
                        }
                        a.color = a.icon.slice(a.icon.length - 2)
                        a.icon = a.icon.slice(0, a.icon.length - 2)
                    })
                    this.apps = array
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
        this.getApps()
    },
    data: () => ({
        apps: [],
        openDialog: false
    }),
}
</script>

<style scoped>
.apps-container .vert.appbox {
    background-color: var(--color-primary-category) !important;
}

.apps-container .bleu.appbox {
    background-color: var(--color-secondary) !important;
}
</style>

<style>
.appbox>span {
    max-width: 100%;
}
</style>