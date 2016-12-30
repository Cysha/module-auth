<template>
    <grid :position="grid">
        <div class="small-box bg-aqua clickable" v-on:click="openUrl">
            <div class="inner">
                <h3>{{ count }}</h3>
                <p>Users Registered Today</p>
            </div>
            <div class="icon">
                <i class="fa fa-user-plus"></i>
            </div>
        </div>
    </grid>
</template>

<script>
import Grid from '../../../../Admin/Resources/dashboard/js/mixins/grid.js';
import API from '../../../../Admin/Resources/dashboard/js/mixins/api.js';

export default {
    name: 'auth-daily-register-count',
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
            this.api('get', '/api/widget/auth/daily-user-count', {}).then((response) => {
                this.count = response.body.data.user_count || 0;
            }, (response) => {
                console.log(['failed', response]);
            });
        },
    }
};
</script>

