<template>
    <v-col class="maincol" :md="archivesMode ? 12 : 8" sm="12">
        <v-card outlined :color="$vuetify.theme.dark ? '' : '#0eb4eda1'" style="height:100%">
            <v-card-title>
                <v-card class="pa-2">Actualités</v-card>
                <Searchbar @searchfilters="Filters"></Searchbar>
                <v-btn :text="$vuetify.theme.dark" class="mr-2" @click="archivesMode = !archivesMode; archives = archives.slice(0, 1); $emit('closealerts', archivesMode)"
                    :color="$vuetify.theme.dark ? 'accent' : ''" large>
                    <v-icon class="mr-2" v-if="!archivesMode">mdi-archive</v-icon>
                    {{ archivesMode ? 'Retour' : 'Archives' }}
                </v-btn>
                <v-btn fab small elevation="1" @click="openDialog = 5; newsToUpdate = EmptyNews">
                    <v-icon>mdi-plus</v-icon>
                </v-btn>
            </v-card-title>
            <v-card-text class="newscarousel" v-if="!archivesMode">
                <v-hover v-slot="{ hover }">
                    <v-carousel :cycle="!hover" :continuous="true" :show-arrows="false" hide-delimiters v-model="newsForward" style="height: 100%;">
                        <v-carousel-item v-for="(n, x) in news" :key="x">
                            <v-card class="mx-auto" elevation="4" :color="$vuetify.theme.dark ? '#0eb4eda1' : ''" style="height: 100%; position: relative;">
                                <v-carousel :continuous="false" :show-arrows="true" hide-delimiter-background delimiter-icon="mdi-minus" height="400" v-if="n.photo.length > 1">
                                    <v-carousel-item v-for="(photo, i) in n.photo" :key="i" @click="LightBoxDialog = true; LightBoxPhotos = n.photo">
                                        <v-img height="400" :src="photo"></v-img>
                                    </v-carousel-item>
                                </v-carousel>
                                <v-img height="400" :src="n.photo[0]" v-else @click="LightBoxDialog = true; LightBoxPhotos = n.photo"></v-img>
                                <v-card-title>{{ n.title }}</v-card-title>
                                <v-card-subtitle>{{ n.subtitle }}</v-card-subtitle>
                                <v-card-text v-html="n.text" style="margin-bottom:20px">
                                </v-card-text>
                                <v-card-actions style="position:absolute; bottom: 0; width: 100%;">
                                    <v-chip label class=" text-truncate">
                                        <span class="text-truncate">
                                            {{ n.category }}
                                        </span>
                                    </v-chip>
                                    <v-icon class="ml-1" v-if="n.pinned">mdi-pin-outline</v-icon>
                                    <v-spacer></v-spacer>
                                    <v-btn small rounded icon class="mr-1" v-if="!n.visible" @click="openDialog = 2; newsToUpdate = n">
                                        <v-icon>mdi-eye-off</v-icon>
                                    </v-btn>
                                    <admin-menu menuType="news" @open="openDialog = $event; newsToUpdate = n">
                                    </admin-menu>
                                    <v-chip>
                                        <span class="text-truncate">
                                            {{ getFormatedDate(n.time) }}
                                        </span>
                                    </v-chip>
                                </v-card-actions>
                            </v-card>
                        </v-carousel-item>
                    </v-carousel>
                </v-hover>
                <v-pagination v-model="newsForwardC" :length="news.length" class="mt-2" :color="$vuetify.theme.dark ? 'secondary darken-1' : 'primary '"></v-pagination>
            </v-card-text>
            <v-card-text v-if="archivesMode">
                <v-list color="transparent">
                    <v-list-item v-for="(archive, i) in archives" :key="i">
                        <v-lazy v-model="lazyload" :options="{
                            threshold: .5
                        }" transition=" fade-transition" width="100%">
                            <div>
                                <v-card class="mt-2" v-for="(a, y) in archive" :key="y" :color="$vuetify.theme.dark ? '#0eb4eda1' : ''" @click="focus = i + '-' + y">
                                    <v-carousel :continuous="false" :show-arrows="true" hide-delimiter-background delimiter-icon="mdi-minus" height="400"
                                        v-if="a.photo.length > 1 && focus == i + '-' + y">
                                        <v-carousel-item v-for="(photo, i) in a.photo" :key="i" @click="LightBoxDialog = true; LightBoxPhotos = a.photo">
                                            <v-img height="400" :src="photo"></v-img>
                                        </v-carousel-item>
                                    </v-carousel>
                                    <v-img height="400" :src="a.photo[0]" v-if="a.photo.length <= 1 && focus == i + '-' + y" @click="LightBoxDialog = true; LightBoxPhotos = a.photo"></v-img>
                                    <v-card-title class="text-truncate">{{ a.title }}
                                        <v-spacer></v-spacer>
                                        <v-icon class="mr-1" v-if="a.pinned">mdi-pin-outline</v-icon>
                                        <v-chip label class="text-truncate">
                                            <span class="text-truncate">
                                                {{ a.category }}
                                            </span>
                                        </v-chip>
                                        <v-chip label class="mx-2">
                                            <span class="text-truncate">
                                                {{ getFormatedDate(a.time) }}
                                            </span>
                                        </v-chip>
                                        <v-btn small rounded icon class="mr-1" v-if="!a.visible" @click="openDialog = 2, newsToUpdate = a">
                                            <v-icon>mdi-eye-off</v-icon>
                                        </v-btn>
                                        <admin-menu menuType="news" @open="openDialog = $event; newsToUpdate = a"></admin-menu>
                                    </v-card-title>
                                    <v-card-subtitle>{{ a.subtitle }}</v-card-subtitle>
                                    <v-card-text :class="focus == i + '-' + y ? '' : 'text-truncate'" v-html="a.text" style="overflow: hidden;max-width: 100%;"></v-card-text>
                                </v-card>
                            </div>
                        </v-lazy>
                    </v-list-item>
                </v-list>
            </v-card-text>
        </v-card>
        <vue-easy-lightbox escDisabled moveDisabled :visible="LightBoxDialog" :imgs="LightBoxPhotos" :index="0" @hide="LightBoxDialog = false"></vue-easy-lightbox>
        <admin-change :open="openDialog == 3" @close="openDialog = -1" @change="PinNews" :title="'Modification de l\'épinglage de l\'actualité'"
            :msg="false ? 'Voulez vous vraiment désépingler cette actualité (celle-ci ne sera plus visible pour les utilisateurs): <br/><code>' + newsToUpdate.title + '</code>' : 'Voulez vous vraiment épingler cette actualité : <br/><code>' + newsToUpdate.title + '</code>'"
            :validate="false ? 'Désépingler' : 'Epingler'" :color="false ? 'red darken-1' : 'green darken-1'">
        </admin-change>
        <admin-change :open="openDialog == 2" @close="openDialog = -1" @change="PublishNews" :title="'Modification de la visibilité de l\'actualité'"
            :msg="false ? 'Voulez vous vraiment mettre en brouillon cette actualité (celle-ci ne sera plus visible pour les utilisateurs): <br/><code>' + newsToUpdate.title + '</code>' : 'Voulez vous vraiment publier cette actualité : <br/><code>' + newsToUpdate.title + '</code>'"
            :validate="false ? 'Mettre en brouillon' : 'Publier'" :color="false ? 'red darken-1' : 'green darken-1'">
        </admin-change>
        <news-modify :open="openDialog == 0 || openDialog == 5" :create="openDialog == 5" @close="openDialog = -1" :news="newsToUpdate" @created="prepare_add" @updated="prepare_update"></news-modify>
        <admin-change :open="openDialog == 1" @close="openDialog = -1" @change="DeleteNews" :title="'Supprimer cette actualité'"
            :msg="'Voulez vous vraiment supprimer cette actualité: <br/><code>' + newsToUpdate.title + '</code>'" validate="Supprimer" color="red darken-1">
        </admin-change>
    </v-col>
</template>

<script>
import AdminChange from './admin/adminChange.vue';
import adminMenu from './admin/adminMenu.vue'
import newsModify from './admin/newsModify.vue';
import moment from 'moment'
import Searchbar from './Searchbar.vue';
import axios from '@nextcloud/axios';
import { generateUrl } from '@nextcloud/router';

export default {
    components: { adminMenu, newsModify, AdminChange, Searchbar },
    name: "News",
    watch: {
        lazyload: function (val) {
            if (val && this.totalNewsLength > (this.lazyArchivesCounter + 5)) {
                this.lazyArchivesCounter += 5
                this.GetArchives()
                console.log(val)
            }
            this.lazyload = false
        },
    },
    computed: {
        newsForwardC: {
            get() {
                return this.newsForward + 1
            },
            set(value) {
                this.newsForward = value - 1
            }
        }
    },
    methods: {
        GetNews() {
            axios.post(generateUrl(`apps/intranetagglo/news/0`), { 'limit': 5, 'search': '', 'categories': '', dateFilter: { start: "", end: "" } }, { type: 'application/json' })
                .then((response) => {
                    console.log(response.data)
                    this.news = response.data[0];
                    this.news.forEach(n => {
                        n.photo = n.photo.split(';')
                        n.groups = n.groups.split(';')
                    })
                })
        },
        GetArchives() {
            axios.post(generateUrl(`apps/intranetagglo/news/${this.lazyArchivesCounter}`), { 'limit': 5, 'search': this.filters.search, 'categories': this.filters.categories, dateFilter: { start: "", end: "" } }, { type: 'application/json' })
                .then((response) => {
                    console.log(response.data)
                    this.totalNewsLength = response.data[1]
                    var array = response.data[0]
                    array.forEach(a => {
                        a.photo = a.photo.split(';')
                        a.groups = a.groups.split(';')
                    })

                    this.archives.push(array);
                })
        },
        PinNews() {
            this.pinDialog = !this.pinDialog
        },
        PublishNews() {
            this.publishDialog = !this.publishDialog
        },
        Filters(search, categories, months) {
            this.archivesMode = true;
            this.filters = {
                search: search,
                categories: categories.join(';'),
                months: months
            }
            this.archives = []
            this.GetArchives()
            console.log(search)
            console.log(categories)
            console.log(months)
        },
        getFormatedDate(date) {
            return moment((date * 1000)).locale('fr').format('LL')
        },
        prepare_add(news) {
            news.groups = news.groups.join(';')
            this.createNews(news)
            this.newsToUpdate = this.EmptyNews
        },
        prepare_update(news) {
            this.updateNews(news)
            this.newsToUpdate = this.EmptyNews
        },
        prepare_delete() {
            this.deleteNews()
        },
        async createNews(news, newimages) {
            console.log(newimages)
            let data = new FormData();
            data.append('title', news.title);
            data.append('subtitle', news.subtitle);
            data.append('text', news.text);
            if (newimages.length) {
                for (var x = 0; x < newimages.length; x++)
                    data.append('photo[]', newimages[x], newimages[x].name);
            }
            data.append('category', news.category);
            data.append('groups', news.groups);
            data.append('link', news.link);
            data.append('expiration', news.expiration);

            axios.post(generateUrl(`apps/intranetagglo/news`), data, {
                headers: {
                    'accept': 'application/json',
                    'Content-Type': `multipart/form-data; boundary=${data._boundary}`,
                }
            }).then(() => {
                this.GetNews()
            })
        },
        async updateNews(news, newimages, deletedIMG) {

            let data = new FormData();
            data.append('title', news.title);
            data.append('subtitle', news.subtitle);
            data.append('text', news.text);
            if (newimages.length) {
                for (var x = 0; x < newimages.length; x++)
                    data.append('photo_upd[]', newimages[x], newimages[x].name);
            }
            data.append('photos', news.photo);
            data.append('deletedphoto', deletedIMG);
            data.append('category', news.category);
            data.append('groups', news.groups);
            data.append('link', news.link);
            data.append('expiration', news.expiration);

            axios.post(generateUrl(`apps/intranetagglo/news/update/${news.id}`), data, {
                headers: {
                    'accept': 'application/json',
                    'Content-Type': `multipart/form-data; boundary=${data._boundary}`,
                }
            }).then(() => {
                this.GetNews()
            })
        },
        async deleteNews() {
            axios.delete(generateUrl(`apps/intranetagglo/news/${this.newsToUpdate.id}`), {
                id: this.newsToUpdate.id
            }).then(() => {
                this.GetNews()
            })
        },
    },
    mounted() {
        this.GetNews()
        this.GetArchives()
    },
    data: () => ({
        openDialog: -1,
        focus: -1,
        newsForward: 0,
        archivesMode: false,
        lazyArchivesCounter: 0,
        lazyload: false,
        LightBoxPhotos: [],
        LightBoxDialog: false,
        news: [],
        totalNewsLength: 0,
        filters: {
            search: '',
            categories: '',
            months: []
        },
        // news: [{
        //     title: "Lorem ipsum dolor sit amet",
        //     subtitle: "Lorem ipsum dolor sit amet azdza d zqd ad zd qzd zqd az d",
        //     text: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque neque felis, ultrices ac volutpat eget, luctus quis augue. Morbi mattis bibendum faucibus. Morbi ultricies, diam id dapibus gravida, lectus neque auctor orci, sed convallis neque nulla ut justo. Maecenas sagittis mauris lectus. Duis eu ullamcorper dui. Nulla et odio nulla. Cras interdum vel libero maximus viverra.",
        //     photo: [require("../assets/LogoCA2BM.png"), require("../assets/IMG20211002181858.jpg")],
        //     category: "Information",
        //     groups: ["intranet-admin"],
        //     link: "",
        //     time: 1272509157,
        //     expiration: 0,
        //     visible: false,
        //     pinned: true
        // }, {
        //     title: "Lorem ipsum dolor sit amet",
        //     subtitle: "je suis au chomage",
        //     text: "Lorem ipsum dolor sit amet BLABLABLA BLABLABL ABLALBLA BLA BL",
        //     photo: [require("../assets/a4page.jpg")],
        //     category: "Information",
        //     groups: ["intranet-admin"],
        //     link: "",
        //     expiration: 0,
        //     time: 1272509157,
        //     visible: false,
        //     pinned: false
        // }],
        archives: [],
        newsToUpdate: {
            title: "",
            subtitle: "",
            text: "",
            photo: [],
            category: "",
            groups: [],
            link: "",
            expiration: 0
        },
        EmptyNews: {
            title: "",
            subtitle: "",
            text: "",
            photo: [],
            category: "",
            groups: [],
            link: "",
            expiration: 0
        },
    }),
}
</script>

<style>
.v-card__text .text-truncate * {
    margin: 0;
    max-width: 100%;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
}

.v-carousel__item {
    height: 100% !important;
}

.newscarousel {
    height: calc(100% - 125px) !important;
}
</style>