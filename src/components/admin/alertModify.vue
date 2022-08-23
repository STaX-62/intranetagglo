<template>
    <v-dialog v-model="dialog" width="800">
        <v-card>
            <v-card-title class="text-h5">
                {{ create ? 'Ajouter' : 'Modifier' }} une <code class="ml-1">Alerte</code>
            </v-card-title>

            <v-card-text>
                <v-form v-model="valid">
                    <v-text-field v-model="modifiedAlert.title" :counter="100" :rules="titleRules" label="Name" required></v-text-field>

                    <v-select v-model="modifiedAlert.groups" :items="groups" label="Groupes d'utilisateurs" required multiple small-chips></v-select>

                    <v-menu ref="menu" v-model="menu" :close-on-content-click="false" :return-value.sync="dateC" transition="scale-transition" offset-y min-width="auto">
                        <template v-slot:activator="{ on, attrs }">
                            <v-combobox class="mb-2" v-model="dateC" small-chips label="Date d'expiration de l'alerte" :rules="[v => !!v || 'Un date d\expiration est nécessaire']"
                                prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on">
                            </v-combobox>
                        </template>
                        <v-date-picker v-model="dateC" no-title scrollable locale="fr">
                            <v-spacer></v-spacer>
                            <v-btn text color="primary" @click="menu = false">
                                Retour
                            </v-btn>
                            <v-btn text color="primary" @click="$refs.menu.save(dateC)">
                                Valider
                            </v-btn>
                        </v-date-picker>

                    </v-menu>
                    <vue-editor v-model="modifiedAlert.text" :editor-toolbar="customToolbar"></vue-editor>
                </v-form>
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="grey" text @click="dialog = false">
                    Retour
                </v-btn>

                <v-btn color="green darken-1" text @click="create ? $emit('created', modifiedAlert) : $emit('updated', modifiedAlert); dialog = false" :disabled="!valid">
                    {{ create ? 'Ajouter' : 'Modifier' }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>

export default {
    name: "newsModify",
    props: {
        open: Boolean,
        create: Boolean,
        alert: Object
    },
    computed: {
        groups() {
            return this.$groups
        },
        dialog: {
            get() {
                return this.open
            },
            set() {
                this.$emit('close')
            }
        },
        modifiedAlert: {
            get() {
                console.log(this.alert.text)
                if (this.updatedAlert === undefined)
                    return this.alert
                else
                    return this.updatedAlert
            },
            set(value) {
                console.log(value.text)
                this.updatedAlert = value
            }
        },
        dateC: {
            get() {
                if (typeof this.modifiedAlert.expiration == 'string')
                    return new Date(parseInt(this.modifiedAlert.expiration) * 1000).toISOString().substring(0, 10);
                else return new Date(this.modifiedAlert.expiration * 1000).toISOString().substring(0, 10);
            },
            set(value) {
                this.modifiedAlert.expiration = new Date(value).getTime() / 1000
            }
        }
    },
    data: () => ({
        updatedAlert: undefined,
        menu: false,
        valid: false,
        titleRules: [
            v => !!v || 'Un titre est nécessaire',
            v => (v && v.length <= 100) || 'Un titre trop long risque de poser des problèmes d\'affichage',
        ],
        customToolbar: [
            [{ header: [false, 1, 2, 3, 4, 5, 6] }],
            ["bold", "italic", "underline", "strike"], // toggled buttons
            [
                { align: "" },
                { align: "center" },
                { align: "right" },
                { align: "justify" }
            ],
            ["blockquote", "code-block"],
            [{ list: "ordered" }, { list: "bullet" }],
            [{ indent: "-1" }, { indent: "+1" }], // outdent/indent
            [{ color: [] }, { background: [] }], // dropdown with defaults from theme
            ["link"],
            ["clean"] // remove formatting button
        ],
    }),
}
</script>