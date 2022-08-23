<template>
    <v-menu bottom :close-on-click="closeOnClick">
        <template v-slot:activator="{ on, attrs }">
            <v-btn small rounded icon class="mr-1" v-bind="attrs" v-on="on">
                <v-icon>mdi-cog-outline</v-icon>
            </v-btn>
        </template>

        <v-list dense>
            <v-list-item link @click="$emit('open', 3)" v-if="menuType == 'news'">
                <v-icon class=" mr-2" color="secondary">{{ published ? "mdi-pin-off-outline" : "mdi-pin-outline" }}</v-icon>
                <v-list-item-title>{{ pin ? "Désépingler" : "Epingler" }}</v-list-item-title>
            </v-list-item>
            <v-list-item link @click="$emit('open', 2)" v-if="menuType == 'news'">
                <v-icon class=" mr-2" color="secondary">{{ published ? "mdi-eye-off" : "mdi-eye" }}</v-icon>
                <v-list-item-title>{{ published ? "Mettre en Brouillon" : "Publier" }}</v-list-item-title>
            </v-list-item>
            <v-list-item link @click="$emit('open', 0)">
                <v-icon class="mr-2" color="primary">mdi-pencil</v-icon>
                <v-list-item-title>Modifier</v-list-item-title>
            </v-list-item>
            <v-list-item link @click="$emit('open', 1)">
                <v-icon class="mr-2" color="red darken-4">mdi-delete</v-icon>
                <v-list-item-title>Supprimer</v-list-item-title>
            </v-list-item>
        </v-list>
    </v-menu>
</template>

<script>
export default {
    name: "adminMenu",
    props: {
        menuType: {
            validator(value) {
                // The value must match one of these strings
                return ['alert', 'news', 'app', 'nav'].includes(value)
            },
            required: true
        },
        published: Boolean,
        pin: Boolean
    },
    data: () => ({
        closeOnClick: true
    }),
}
</script>

<style>
</style>