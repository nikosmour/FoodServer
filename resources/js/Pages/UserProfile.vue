<template>
    <div>
        <h1>Welcome, {{ currentUser.name }}!</h1>
        <models-to-table :models="[currentUser]" caption="User Information"/>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import ModelsToTable from "../Components/modelsToTable.vue";

export default {
    components: {ModelsToTable},
    computed: {
        ...mapGetters([
            'currentUser',
        ]),
    },
};
</script>
