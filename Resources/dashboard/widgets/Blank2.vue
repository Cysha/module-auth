<template>
    <grid :position="grid">
        <section class="blank">
            <span class="blank__content">
                {{ title }}
            </span>
        </section>
    </grid>
</template>

<script>
import Grid from '../../../../Admin/Resources/dashboard/js/mixins/grid.js';

export default {
    name: 'auth-blank2',
    components: {
        Grid,
    },
    props: ['grid', 'title'],

    data() {
        return {

        };
    },

    created() {
    }
};
</script>
