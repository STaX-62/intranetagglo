<template>
    <v-text-field v-model="search" @keyup="onChange" class="Searchbar" label="Recherche..." single-line solo prepend-inner-icon="mdi-magnify" color="accent" hide-details>
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
                    <v-chip-group v-model="chip" column @change="onChange">
                        <v-chip class="mx-1" filter outlined small v-for="n in $categories" :key="n">
                            {{  n  }}
                        </v-chip>
                    </v-chip-group>
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
                this.$emit('searchfilters', this.search, this.chip == undefined ? '' : this.$categories[this.chip], this.months)
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
        chip: undefined,
    }),
}
</script>

<style>
.Searchbar {
    margin-right: 8px !important;
    margin-left: 8px !important;
}

@media (max-width: 694px) {
    .Searchbar {
        margin-right: 0 !important;
        margin-left: 0 !important;
    }
}
</style>