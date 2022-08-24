<template>
    <v-dialog v-model="dialog" width="800">
        <v-card>
            <v-card-title class="text-h5">
                {{ create ? 'Ajouter' : 'Modifier' }} un <code class="ml-1">Menu</code>
            </v-card-title>

            <v-card-text>
                <v-form v-model="valid">
                    <v-text-field v-model="modifiedNav.title" :counter="40" :rules="titleRules" label="Name" required></v-text-field>
                    <v-text-field v-model="modifiedNav.icon" label="Icon" required></v-text-field>
                    <v-text-field v-model="modifiedNav.link" label="Lien" required></v-text-field>
                    <v-select v-model="modifiedNav.groups" :items="$groups" label="Groupes d'utilisateurs" required multiple smavaluell-chips></v-select>
                </v-form>
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="grey" text @click="dialog = false">
                    Retour
                </v-btn>

                <v-btn color="green darken-1" text @click="create ? $emit('created', modifiedNav) : $emit('updated', modifiedNav); dialog = false" :disabled="!valid">
                    {{ create ? 'Ajouter' : 'Modifier' }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
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
            get() {
                if (this.updatedNav === undefined)
                    return this.nav
                else
                    return this.updatedNav
            },
            set(value) {
                this.updatedNav = value
            }
        }
    },
    data: () => ({
        updatedNav: undefined,
        valid: false,
        titleRules: [
            v => !!v || 'Un titre est nécessaire',
            v => (v && v.length <= 100) || 'Un titre trop long risque de poser des problèmes d\'affichage',
        ],
    }),
}
</script>