<template>
    <grid :position="grid">
        <div class="small-box bg-yellow clickable" v-on:click="openUrl">
            <div class="inner">
                <h3>{{ count }}</h3>
                <p>User Registrations</p>
            </div>
            <div class="icon">
                <i class="fa fa-users"></i>
            </div>
        </div>
    </grid>
</template>

<script>
import Grid from '../../../../Admin/Resources/dashboard/js/mixins/grid.js';
import API from '../../../../Admin/Resources/dashboard/js/mixins/api.js';

export default {
    name: 'auth-user-count',
    components: {
        Grid
    },
    mixins: [API],
    props: ['grid'],

    data() {
        return {
            count: 0
        };
    },

    created() {
        this.getCount();
    },

    methods: {
        openUrl() {
            document.location = '/admin/users';
        },

        getCount() {
            this.api('get', '/api/widget/auth/user-count', {}).then((response) => {
                this.count = response.body.data.user_count || 0;
            }, (response) => {
                console.log(['failed', response]);
            });
        },
    }
};
</script>


<style>
    .clickable {
        cursor: pointer;
    }
</style>
