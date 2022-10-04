<template>
    <v-dialog v-model="dialog" width="800">
        <v-card>
            <v-card-title class="text-h5">
                {{ create ? 'Ajouter' : 'Modifier' }} un <code class="ml-1">Menu</code>
            </v-card-title>

            <v-card-text>
                <v-form v-model="valid">
                    <v-text-field v-model="modifiedNav.title" :counter="40" :rules="titleRules" label="Name" required></v-text-field>
                    <icon-help v-if="modifiedNav.level == 0"></icon-help>
                    <v-text-field v-if="modifiedNav.level == 0" v-model="modifiedNav.icon" label="Icon" required></v-text-field>
                    <v-text-field v-if="!modifiedNav.childs" v-model="modifiedNav.link" label="Lien" required></v-text-field>
                    <v-select v-model="modifiedNav.groups" :items="$groups" label="Groupes d'utilisateurs" multiple small-chips></v-select>
                </v-form>
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="grey" text @click="dialog = false">
                    Retour
                </v-btn>

                <v-btn color="green darken-1" text @click="create ? $emit('created') : $emit('updated'); dialog = false; " :disabled="!valid">
                    {{ create ? 'Ajouter' : 'Modifier' }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import iconHelp from './iconHelp.vue'
export default {
    components: { iconHelp },
    name: "navModify",
    props: {
        open: Boolean,
        create: Boolean,
        nav: Object
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
        modifiedNav: {
            get() { return this.nav },
            set(value) { this.$emit('update:nav', value) },
        }
    },
    data: () => ({
        valid: false,
        titleRules: [
            v => !!v || 'Un titre est nécessaire',
            v => (v && v.length <= 100) || 'Un titre trop long risque de poser des problèmes d\'affichage',
        ],
    }),
}
</script>