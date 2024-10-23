<template>
    <div>
      <h1>{{ $t('Welcome') }}, {{ currentUser.name }}!</h1>
        <models-to-table :caption="$t('user.information')" :models="[currentUser]"/>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import ModelsToTable from "../Components/modelsToTable.vue";

export default {
    components: {ModelsToTable},
    computed: {
        ...mapGetters('auth', [
            'currentUser',
        ]),
    },
};
</script>
