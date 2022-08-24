<template>
    <v-navigation-drawer app right>
        <h2 class="mt-2 text-center">Applications
        </h2>
        <v-row class="ma-1 apps-container">
            <v-col v-for="(app, index) in apps" :key="index" cols="6">
                <v-responsive :aspect-ratio="1 / 1">
                    <v-btn class="appbox" style="height: 100%;width:100%;" :href="app.link">
                        <div class="text-center d-flex flex-column align-center justify-center text-wrap" style="height: 100%; letter-spacing: normal; font-size:smaller;">
                            <v-icon class="pb-1">{{ app.icon }}</v-icon>
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
        <apps-dialog :open="openDialog" @close="openDialog = false"></apps-dialog>
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
        getNav() {
            axios.get(generateUrl(`apps/intranetagglo/appsG`))
                .then((response) => {
                    this.apps = response.data;
                })
        }
    },
    mounted() {
        this.getNav()
    },
    data: () => ({
        openDialog: false
    }),
}
</script>

<style scoped>
.appbox {
    background-color: var(--color-primary-category) !important;
}

.apps-container div:nth-of-type(1n + 5) .appbox {
    background-color: var(--color-secondary) !important;
}
</style>
<style>
.appbox>span {
    max-width: 100%;
}
</style>