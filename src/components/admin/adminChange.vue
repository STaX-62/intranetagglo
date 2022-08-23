<template>
    <v-dialog v-model="dialog" max-width="500">
        <v-card>
            <v-card-title class="text-h5">
                {{ title }}
            </v-card-title>

            <v-card-text v-html="msg">
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn color="grey" text @click="dialog = false">
                    Retour
                </v-btn>

                <v-btn :color="color" text @click="$emit('changed')">
                    {{ validate }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    name: "adminChange",
    props: {
        open: Boolean,
        msg: String,
        title: String,
        validate: String,
        color: String
    },
    computed: {
        dialog: {
            get() {
                return this.open
            },
            set() {
                this.$emit('close')
            }
        }
    }
}
</script>

<style>
</style>