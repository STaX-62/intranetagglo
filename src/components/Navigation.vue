<template>
    <v-navigation-drawer app>
        <v-list-item>
            <v-list-item-content>
                <v-img :src="image"></v-img>
            </v-list-item-content>
        </v-list-item>

        <v-divider></v-divider>
        <v-list dense>
            <v-list-group :value="true" prepend-icon="mdi-account-multiple">
                <template v-slot:activator style="margin-right: 15px;">
                    <v-list-item-content>
                        <v-list-item-title class="text-subtitle-1">Vie des Assemblées</v-list-item-title>
                    </v-list-item-content>
                </template>

                <v-list-group sub-group no-action>
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title>Elus</v-list-item-title>
                        </v-list-item-content>
                    </template>

                    <v-list-item link>
                        <v-list-item-title>Bureau Communautaire </v-list-item-title>
                    </v-list-item>
                    <v-list-item link>
                        <v-list-item-title>Conseil d'Agglomération</v-list-item-title>
                    </v-list-item>
                </v-list-group>
                <v-list-group sub-group no-action>
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title>Actes</v-list-item-title>
                        </v-list-item-content>
                    </template>

                    <v-list-item link>
                        <v-list-item-title>Délibération</v-list-item-title>
                    </v-list-item>
                    <v-list-item link>
                        <v-list-item-title>Arrêtés</v-list-item-title>
                    </v-list-item>
                    <v-list-item link>
                        <v-list-item-title>Décisions</v-list-item-title>
                    </v-list-item>
                    <v-list-item link>
                        <v-list-item-title>Arrêtés préfectoraux</v-list-item-title>
                    </v-list-item>
                </v-list-group>
                <v-list-group sub-group no-action>
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title>Conseil D'Agglomération</v-list-item-title>
                        </v-list-item-content>
                    </template>

                    <v-list-item link>
                        <v-list-item-title>Prépa Conseil</v-list-item-title>
                    </v-list-item>
                    <v-list-item link>
                        <v-list-item-title>CR Conseils</v-list-item-title>
                    </v-list-item>
                </v-list-group>
                <v-list-item link style="padding-left:66px;">
                    <v-list-item-title>Prépa Conseil</v-list-item-title>
                </v-list-item>
            </v-list-group>

        </v-list>
        <template v-slot:append>
            <div class="d-flex py-3">
                <v-btn text class="mx-auto" color="primary" @click="openDialog = true">
                    <v-icon class="mr-2">mdi-cog-outline</v-icon>
                    Modifer les menus
                </v-btn>
                <nav-dialog :open="openDialog" @close="openDialog = false"></nav-dialog>
            </div>
            <v-divider></v-divider>
            <div class="d-flex">
                <v-switch class="ml-2" v-model="$vuetify.theme.dark" :prepend-icon="$vuetify.theme.dark ? 'mdi-weather-night' : 'mdi-white-balance-sunny'" />
                <v-spacer></v-spacer>
                <v-btn icon class="my-auto mr-2">
                    <v-icon>mdi-help-circle</v-icon>
                </v-btn>
            </div>
        </template>
    </v-navigation-drawer>
</template>

<script>
import navDialog from './admin/navDialog.vue'
export default {
    name: "Navigation",
    components: { navDialog },
    data: () => ({
        openDialog: false,
        image: '/apps/intranetagglo/img/LogoCA2BM.png'//'/apps/intranetagglo/img/LogoCA2BM.png',
    }),
}
</script>

<style>
.v-list-item__icon:first-child {
    margin-right: 10px !important;

}

.v-list-item__icon {
    min-width: 24px !important;
    margin-left: 0 !important;
}
</style>