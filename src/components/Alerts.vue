<template>
    <v-col md="4" sm="12" v-if="alerts.length || $isAdmin">
        <v-card elevation="0" style="height:100%"
            data-intro="Ici sont disponibles les informations/alertes temporaires, celles-ci expireront au bout d'une certaine période"
            data-title="Alertes" data-step="2">
            <!-- <v-card-title>
                <v-card class="pa-2">Alertes</v-card>
                <v-spacer></v-spacer>
                <v-btn fab small elevation="1" @click="openDialog = 5; alertToUpdate = alertEmpty" v-if="$isAdmin">
                    <v-icon>mdi-plus</v-icon>
                </v-btn>
            </v-card-title> -->
            <v-card v-for="(alert, index) in alerts" :key="index" class="mx-auto mb-2"
                :color="$vuetify.theme.dark ? '#9ecd4399' : ''">
                <v-card-title>{{ alert.title }}</v-card-title>
                <v-card-text class="news-text" v-html="alert.text" style="padding-bottom:0">
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn small rounded icon class="mr-1" v-if="alert.visible == '0' && $isAdmin"
                        @click="openDialog = 2, alertToUpdate = alert">
                        <v-icon>mdi-eye-off</v-icon>
                    </v-btn>
                    <admin-menu menuType="alert" @open="openDialog = $event; alertToUpdate = alert" v-if="$isAdmin"
                        :published="alert.visible == '1'"></admin-menu>
                    <v-chip>Expire dans {{ getFormatedDate(alert.expiration) }}</v-chip>
                </v-card-actions>
            </v-card>
        </v-card>
        <alert-modify :open="openDialog == 0 || openDialog == 5" :create="openDialog == 5" @close="openDialog = -1"
            @created="prepare_add" @updated="prepare_update" :alert="alertToUpdate" v-if="$isAdmin">
        </alert-modify>
        <admin-change :open="openDialog == 1" @close="openDialog = -1" @changed="prepare_delete"
            :title="'Supprimer cette alerte'" :msg="'Voulez vous vraiment supprimer cette alerte: '"
            validate="Supprimer" color="red darken-1" v-if="$isAdmin">
        </admin-change>
        <admin-change :open="openDialog == 2" @close="openDialog = -1" @changed="prepare_visible"
            :title="'Modification de la visibilité de l\'alerte'"
            :msg="alertToUpdate.visible == '1' ? 'Voulez vous vraiment mettre en brouillon cette alerte (celle-ci ne sera plus visible pour les utilisateurs): <br/><code>' + alertToUpdate.title + '</code>' : 'Voulez vous vraiment publier cette actualité : <br/><code>' + alertToUpdate.title + '</code>'"
            :validate="alertToUpdate.visible == '1' ? 'Mettre en brouillon' : 'Publier'"
            :color="alertToUpdate.visible == '1' ? 'red darken-1' : 'green darken-1'" v-if="$isAdmin">
        </admin-change>
    </v-col>
</template>

<script>
import adminMenu from './admin/adminMenu.vue'
import AlertModify from './admin/alertModify.vue';
import adminChange from './admin/adminChange.vue';
import axios from '@nextcloud/axios';
import { generateUrl } from '@nextcloud/router';
import FormData from 'form-data';
import moment from 'moment';

export default {
    components: {
        adminMenu, AlertModify, adminChange
    },
    name: "Alerts",
    methods: {
        GetAlerts() {
            axios.post(generateUrl(`apps/intranetagglo/alerts`), { 'search': '' }, { type: 'application/json' })
                .then((response) => {
                    console.log(response.data.length)
                    if (!response.data.length)
                        this.$emit('alertempty', true)
                    this.alerts = response.data;
                    this.alerts.forEach(a => a.groups = a.groups.split(';'))
                })
        },
        getFormatedDate(value) {
            var expiration = moment((value * 1000))
            var diff = expiration.diff(moment().startOf('day'), 'days');
            if (diff == 0)
                return moment().endOf('day').diff(moment(), 'hours') + " heures";
            if (diff == 1)
                return diff + " jour";
            return diff + " jours";
        },
        prepare_visible() {
            this.publishAlert()
        },
        prepare_add(alert) {
            alert.groups = alert.groups.join(';')
            this.createAlert(alert)
            this.alertToUpdate = this.alertEmpty
        },
        prepare_update(alert) {
            this.updateAlert(alert)
            this.alertToUpdate = this.alertEmpty
        },
        prepare_delete() {
            this.deleteAlert()
        },
        async createAlert(alert) {
            let data = new FormData();
            data.append('title', alert.title);
            data.append('subtitle', alert.subtitle);
            data.append('text', alert.text);
            data.append('category', alert.category);
            data.append('groups', alert.groups);
            data.append('link', alert.link);
            if (typeof alert.expiration == 'string')
                data.append('expiration', new Date(parseInt(alert.expiration) * 1000).toISOString());
            else data.append('expiration', new Date(alert.expiration * 1000).toISOString());

            axios.post(generateUrl(`apps/intranetagglo/news`), data, {
                headers: {
                    'accept': 'application/json',
                    'Content-Type': `multipart/form-data; boundary=${data._boundary}`,
                }
            }).then(() => {
                this.GetAlerts()
            })
        },
        async updateAlert(alert) {
            let data = new FormData();
            data.append('title', alert.title);
            data.append('subtitle', alert.subtitle);
            data.append('text', alert.text);
            data.append('photos', "");
            data.append('deletedphoto', []);
            data.append('category', alert.category);
            data.append('groups', alert.groups);
            data.append('link', alert.link);
            if (typeof alert.expiration == 'string')
                data.append('expiration', new Date(parseInt(alert.expiration) * 1000).toISOString());
            else data.append('expiration', new Date(alert.expiration * 1000).toISOString());

            axios.post(generateUrl(`apps/intranetagglo/news/update/${alert.id}`), data, {
                headers: {
                    'accept': 'application/json',
                    'Content-Type': `multipart/form-data; boundary=${data._boundary}`,
                }
            }).then(() => {
                this.GetAlerts()
            })
        },
        async deleteAlert() {
            axios.delete(generateUrl(`apps/intranetagglo/news/${this.alertToUpdate.id}`), {
                id: this.alertToUpdate.id
            }).then(() => {
                this.GetAlerts()
            })
        },
        async publishAlert() {
            var url = `apps/intranetagglo/news/pub/${this.alertToUpdate.id}`
            await axios.post(generateUrl(url), { 'id': this.alertToUpdate.id, 'visible': this.alertToUpdate.visible == '1' ? 0 : 1 }, { type: 'application/json' }).then(() => {
                this.GetAlerts()
            })
        },
    },
    mounted() {
        this.GetAlerts()
    },
    data: () => ({
        openDialog: -1,
        updating: false,
        alerts: [],
        alertToUpdate: {
            id: 0,
            title: "",
            subtitle: "",
            text: "",
            photo: [],
            category: "",
            groups: [],
            link: "",
            expiration: 0
        },
        alertEmpty: {
            title: "",
            subtitle: "",
            text: "",
            photo: [],
            category: "",
            groups: [],
            link: "",
            expiration: Math.floor(Date.now() / 1000)
        }
    }),
}
</script>

<style>

</style>