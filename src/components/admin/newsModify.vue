<template>
    <v-dialog v-model="dialog" width="800">
        <v-card>
            <v-card-title class="text-h5">
                {{ create ? 'Ajouter' : 'Modifier' }} une <code class="ml-1">Actualité</code>
            </v-card-title>

            <v-card-text>
                <v-stepper v-model="stepper">
                    <v-stepper-header>
                        <v-stepper-step :complete="stepper > 1" step="1">
                            Entête
                        </v-stepper-step>

                        <v-divider></v-divider>

                        <v-stepper-step :complete="stepper > 2" step="2">
                            Contenu
                        </v-stepper-step>

                        <v-divider></v-divider>

                        <v-stepper-step step="3">
                            Images
                        </v-stepper-step>
                    </v-stepper-header>

                    <v-stepper-items>
                        <v-stepper-content step="1">
                            <v-form v-model="valid">
                                <v-text-field v-model="modifiedNews.title" :counter="100" :rules="titleRules" label="Titre" required>
                                </v-text-field>
                                <v-text-field v-model="modifiedNews.subtitle" :counter="200" label="Sous-Titre"></v-text-field>
                                <v-text-field v-model="modifiedNews.category" :counter="20" :rules="categoryRules" label="Catégorie"></v-text-field>

                                <v-select v-model="modifiedNews.groups" :items="values" label="Groupes D'utilisateurs" required multiple small-chips>
                                </v-select>
                            </v-form>
                        </v-stepper-content>

                        <v-stepper-content step="2">
                            <vue-editor v-model="modifiedNews.text" :editor-toolbar="customToolbar"></vue-editor>
                        </v-stepper-content>

                        <v-stepper-content step="3">
                            <div v-if="modifiedNews.photo.length">
                                <div for="preview">Selection actuelle :
                                    <b-button @click="returndeletedIMG(p)" v-if="deletedIMG.length">
                                        <i class="mdi mdi-keyboard-return"></i>
                                    </b-button>
                                </div>
                                <draggable v-model="modifiedNews.photo" class="grid-tile" group="oldimg" :move="onMoveCallback" style="display: flex;">
                                    <div v-for="p in modifiedNews.photo" :key="p" class="imgtile">
                                        <i class="mdi mdi-close" style="position: absolute;right: 2px;top:2px;" @click="deleteExistingIMG(p)"></i>
                                        <img name="preview" :src="p" style="width: 100px; height:100px; margin:auto" />
                                    </div>
                                </draggable>
                            </div>
                            <v-file-input v-model="newimages" multiple accept="image/png, image/jpeg, image/bmp" outlined prepend-icon="mdi-camera"></v-file-input>
                            <div v-if="newimages.length">
                                <div for="preview">Nouvelle Selection : </div>
                                <draggable v-model="newimages" class="grid-tile" group="newimg" :move="onMoveCallback" style="display: flex;">
                                    <div v-for="p in newimages" :key="p" class="imgtile">
                                        <i class="mdi mdi-close" style="position: absolute;right: 2px;top:2px;" @click="deleteNewIMG(p)"></i>
                                        <img name="preview" :src="GetURL(p)" style="width: 100px; height:100px; margin:auto" />
                                    </div>
                                </draggable>
                            </div>
                        </v-stepper-content>
                    </v-stepper-items>
                </v-stepper>
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="grey" text @click="dialog = false">
                    Fermer
                </v-btn>
                <v-btn color="grey" text @click="stepper--" v-if="stepper > 1">
                    Retour
                </v-btn>
                <v-btn color="green darken-1" text @click="stepper++" v-if="stepper < 3" :disabled="!valid">
                    Suivant
                </v-btn>
                <v-btn color="green darken-1" text @click="create ? $emit('created', modifiedNews, newimages) : $emit('updated', modifiedNews, newimages, deletedIMG); dialog = false; stepper = 1"
                    :disabled="!valid" v-if="stepper == 3">
                    {{ create ? 'Ajouter' : 'Modifier' }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import draggable from 'vuedraggable';
export default {
    name: "newsModify",
    components: { draggable },
    props: {
        open: Boolean,
        create: Boolean,
        news: Object
    },
    computed: {
        dialog: {
            get() {
                return this.open
            },
            set() {
                this.$emit('close')
            }
        },
        modifiedNews: {
            get() {
                if (this.updatedNews === undefined)
                    return this.news
                else
                    return this.updatedNews
            },
            set(value) {
                this.updatedNews = value
            }
        }
    },
    methods: {
        GetURL(file) {
            return URL.createObjectURL(file)
        },
        deleteExistingIMG(file) {
            console.log(file)
            console.log(this.modifiedNews.photo.findIndex(f => f == file))
            var photoIndex = this.modifiedNews.photo.findIndex(f => f == file)
            console.log(photoIndex)
            this.deletedIMG.push(this.modifiedNews.photo[photoIndex])
            this.modifiedNews.photo.splice(photoIndex, 1)
        },
        returndeletedIMG() {
            this.modifiedNews.photo.push(this.deletedIMG.pop())
        },
        deleteNewIMG(file) {
            this.newimages.splice(this.newimages.findIndex(f => f.name == file.name), 1)
        },
        onMoveCallback(event) {
            console.log(event)
        }
    },
    data: () => ({
        valid: false,
        stepper: 1,
        updatedNews: undefined,
        picker: null,
        menu: false,
        newimages: [],
        deletedIMG: [],
        titleRules: [
            v => !!v || 'Un titre est nécessaire',
            v => (v && v.length <= 100) || 'Un titre trop long risque de poser des problèmes d\'affichage',
        ],
        categoryRules: [
            v => !!v || 'Un Catégorie est nécessaire',
            v => (v && v.length <= 20) || 'Une catégorie trop longue ne sera tronquée',
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
        values: [
            'intranet-admin',
            'users du domaine',
            'mdr'
        ]
    }),
}
</script>

<style>
.imgtile {
    padding: 10px;
    margin: 5px;
    border: 1px #000 solid;
    position: relative;
    border-radius: 5px;
}
</style>