<template>
    <v-navigation-drawer app>
        <v-list-item>
            <v-list-item-content>
                <v-img :src="image"></v-img>
            </v-list-item-content>
        </v-list-item>

        <v-divider></v-divider>
        <v-list class="section-div" expand dense data-intro="Les liens utiles adaptés à vos besoins triés en fonction de votre service" data-title="Menu de Navigation" data-step="1">
            <v-list-group v-for="(section, index) in MenuToDisplay" :key="'B' + index" :value="true" :prepend-icon="'mdi-' + section.icon">
                <template v-slot:activator>
                    <v-list-item-content>
                        <v-list-item-title class="section-title" @click="OpenLink(section.link, isEmpty(MenuToDisplay[index].childs))"
                            style="font-family: PetitaBold !important;  letter-spacing: .01rem; -webkit-font-smoothing: antialiased !important;font-size: 18px !important; z-index: 1;color:white;">{{
                                    section.title
                            }}
                        </v-list-item-title>
                    </v-list-item-content>
                </template>
                <div v-for="(menu, subindex) in MenuToDisplay[index].childs" :key="'B' + subindex">
                    <v-list style="padding:0">
                        <v-list-group :sub-group="menu.childs.length" no-action v-if="menu.childs.length">
                            <template v-slot:activator>
                                <v-list-item-content>
                                    <v-list-item-title @click="OpenLink(menu.link, isEmpty(MenuToDisplay[index].childs[subindex].childs))"
                                        style="font-family: PetitaBold;  letter-spacing: .01rem; -webkit-font-smoothing: antialiased;font-size: 15px;">{{ menu.title }}
                                    </v-list-item-title>
                                </v-list-item-content>
                            </template>

                            <v-list-item v-for="(submenu, subsubindex) in MenuToDisplay[index].childs[subindex].childs" :key="'B' + subsubindex" link :href="submenu.link" target="_blank"
                                style="padding-left:66px;min-height:30px; font-family: PetitaBold;  letter-spacing: .01rem; -webkit-font-smoothing: antialiased;font-size: 15px;">
                                <v-list-item-title>{{ submenu.title }}</v-list-item-title>
                            </v-list-item>
                        </v-list-group>
                        <v-list-item link style="min-height:30px; font-family: PetitaBold;font-size: 0.8925rem;letter-spacing: 0.08rem;" v-else>
                            <v-list-item-title @click="OpenLink(menu.link, isEmpty(MenuToDisplay[index].childs[subindex].childs))"
                                style="font-family: PetitaBold; letter-spacing: .01rem; -webkit-font-smoothing: antialiased;font-size: 15px;">{{ menu.title }}
                            </v-list-item-title>
                        </v-list-item>
                    </v-list>
                </div>
            </v-list-group>

        </v-list>
        <template v-slot:append>
            <div class="d-flex py-3" v-if="$isAdmin">
                <v-btn text class="mx-auto" color="primary" @click="openDialog = true" style="font-weight: 600">
                    <v-icon class="mr-2">mdi-cog-outline</v-icon>
                    Modifer les menus
                </v-btn>
                <nav-dialog :open="openDialog" @close="openDialog = false" @changed="getMenus"></nav-dialog>
            </div>
            <v-divider></v-divider>
            <div class="d-flex">
                <v-switch class="ml-2" v-model="$vuetify.theme.dark" :prepend-icon="$vuetify.theme.dark ? 'mdi-weather-night' : 'mdi-white-balance-sunny'" @change="SaveDarkmode" />
                <v-spacer></v-spacer>
                <v-btn icon class="my-auto mr-2" @click="Intro">
                    <v-icon>mdi-help-circle</v-icon>
                </v-btn>
            </div>
        </template>
    </v-navigation-drawer>
</template>

<script>
import navDialog from './admin/navDialog.vue'
import axios from '@nextcloud/axios';
import { generateUrl } from '@nextcloud/router';
export default {
    name: "Navigation",
    components: { navDialog },
    computed: {
        isAdmin() {
            return this.$isAdmin
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
        Intro() {
            this.$introJs.start()
        },
        SaveDarkmode() {
            localStorage.setItem('intranetagglo_color_scheme', this.$vuetify.theme.dark ? 'dark' : 'light');
        },
        getMenus() {
            axios.get(generateUrl(`apps/intranetagglo${'/menusG'}`))
                .then((response) => {
                    this.menus = response.data
                })
        },
        isEmpty(array) {
            if (array.length > 0) {
                return false
            }
            else return true
        },
        OpenLink(link, isEmpty) {
            if (link != '' && isEmpty) {
                window.open(link, '_blank');
            }
        },
    },
    mounted() {
        this.getMenus()
    },
    data: () => ({
        openDialog: false,
        menus: [[], [], []],
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

#app .section-div>div>div:hover:focus:before,
#app .section-div>div>div:hover:not(:focus):before,
#app .section-div>div>div:not(:hover):focus:before,
#app .section-div>div>div:not(:hover):not(:focus):before {
    opacity: 1 !important;
    background-color: #0eb4ff !important;
}

#app .section-div>div>div {
    color: white;
}

#app .section-div>div {
    color: white !important;
}

#app .section-div>div>div>i {
    color: white !important;
}
</style>