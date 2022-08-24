<template>
    <v-text-field v-model="search" @keyup="onChange" class="mx-2" label="Recherche..." single-line solo hide-details prepend-inner-icon="mdi-magnify" color="accent">
        <template v-slot:append>
            <v-menu v-model="menu" :close-on-content-click="false" :nudge-width="200" offset-y max-width="290">
                <template v-slot:activator="{ on, attrs }">
                    <v-btn color="accent" icon v-bind="attrs" v-on="on">
                        <v-icon>mdi-filter-variant</v-icon>
                    </v-btn>
                </template>

                <v-card style="max-width: 100%;">
                    <v-date-picker v-model="months" type="month" color="primary" @change="onChange"></v-date-picker>

                    <v-divider></v-divider>

                    <v-list>
                        <v-list-item>
                            <v-item-group v-model="chips" @change="onChange">
                                <v-item v-for="n in $categories" :key="n" v-slot="{ active, toggle }" class="ma-1">
                                    <v-chip active-class="primary--text" :input-value="active" @click="toggle" small>
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