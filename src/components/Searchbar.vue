<template>
    <v-text-field v-model="search" @keyup="onChange" class="mx-2" label="Recherche..." single-line solo prepend-inner-icon="mdi-magnify" color="accent" hide-details>
        <template v-slot:append>
            <v-alert color="grey" dense text type="info" v-if="notfound" style="margin: 0;">Aucun élément trouvé</v-alert>
            <v-menu v-model="menu" :close-on-content-click="false" :nudge-width="200" offset-y max-width="290">
                <template v-slot:activator="{ on, attrs }">
                    <v-btn class="my-auto ml-3" color="accent" icon v-bind="attrs" v-on="on" data-intro="Filtrer les actualités par date de parution ou catégorie" data-title="Filtres" data-step="5">
                        <v-icon>mdi-filter-variant</v-icon>
                    </v-btn>
                </template>

                <v-card style="max-width: 100%;">
                    <v-date-picker v-model="months" type="month" color="primary" @change="onChange"></v-date-picker>

                    <v-divider></v-divider>

                    <v-list>
                        <v-list-item>
                            <v-item-group v-model="chips" @change="onChange">
                                <v-item v-for="n in $categories" :key="n" v-slot="{ toggle }" class="ma-1">
                                    <v-chip active-class="primary--text" :input-value="n" @click="toggle" small>
                                        {{ n }}
                                    </v-chip>
                                </v-item>
                            </v-item-group>
                        </v-list-item>
                    </v-list>
                </v-card>
            </v-menu>
        </template>
    </v-text-field>

</template>

<script>
export default {
    name: "Searchbar",
    methods: {
        onChange() {
            this.timer = setTimeout(() => {
                if (this.$categories.includes(this.chips)) {
                    this.$emit('searchfilters', this.search, this.$categories[this.chips], this.months)
                }
                else {
                    this.$emit('searchfilters', this.search, -1, this.months)
                }
            }, 250)
        }
    },
    props: {
        notfound: Boolean
    },
    data: () => ({
        timer: undefined,
        menu: false,
        search: '',
        months: '',
        chips: '',
    }),
}
</script>

<style>
</style>