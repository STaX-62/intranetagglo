<template>
    <v-banner single-line>
        <v-icon slot="icon" color="accent">
            mdi-vector-square
        </v-icon>
        Documentation icônes (mdi icons v7.0.96)

        <template v-slot:actions>
            <v-btn color="primary" text href="https://materialdesignicons.com/" target="_blank">
                Liste avec recherche
            </v-btn>
            <v-btn color="primary" text href="https://pictogrammers.github.io/@mdi/font/7.0.96/" target="_blank">
                liste complete
            </v-btn>
        </template>
    </v-banner>
</template>

<script>
export default {
    name: "iconHelp"
}
</script>

<style>
</style>