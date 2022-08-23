<template>
    <v-text-field class="mx-2" label="Recherche..." single-line solo hide-details prepend-inner-icon="mdi-magnify" color="accent">
        <template v-slot:append>
            <v-menu v-model="menu" :close-on-content-click="false" :nudge-width="200" offset-y max-width="290">
                <template v-slot:activator="{ on, attrs }">
                    <v-btn color="accent" icon v-bind="attrs" v-on="on">
                        <v-icon>mdi-filter-variant</v-icon>
                    </v-btn>
                </template>

                <v-card style="max-width: 100%;">
                    <v-date-picker v-model="months" type="month" multiple color="primary"></v-date-picker>

                    <v-divider></v-divider>

                    <v-list>
                        <v-list-item>
                            <v-item-group multiple v-model="chips">
                                <v-item v-for="n in chipsitems" :key="n" v-slot="{ active, toggle }" class="ma-1">
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
    data: () => ({
        menu: false,
        months: [],
        chips: [],
        chipsitems: ['Information', 'test', 'Note de service'],
    }),
}
</script>

<style>
</style>