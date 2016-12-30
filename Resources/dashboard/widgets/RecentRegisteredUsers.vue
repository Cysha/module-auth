<template>
    <grid :position="grid">
        <section class="registered-users">
            <ul class="users-list clearfix">
                <li v-if="hasData" v-for="user in userData">
                    <a :href="'/admin/users/'+ user.id +'/edit'"><img :src="user.avatar" class="img-circle img-responsive center-block"></a>
                    <div><a class="users-list-name" :href="'/admin/users/'+ user.id +'/edit'">{{ user.screenname }}</a></div>
                    <small class="muted" v-html="user.registered.element"></small>
                </li>
                <li v-else><div class="alert alert-warning">No users signed up yet.</div></li>
            </ul>
        </section>
    </grid>
</template>

<script>
import Grid from '../../../../Admin/Resources/dashboard/js/mixins/grid.js';
import API from '../../../../Admin/Resources/dashboard/js/mixins/api.js';

export default {
    name: 'auth-recent-registered-users',
    components: {
        Grid
    },
    mixins: [API],
    props: ['grid', 'users'],

    data() {
        return {
            userData: {}
        };
    },

    computed: {
        hasData() {
            return this.userData.length > 0;
        },
    },

    mounted() {
        this.getData();
    },

    methods: {
        getData() {
            this.api('post', '/api/widget/auth/recent-user-list', {
                users: this.users
            }).then((response) => {
                this.userData = response.body.data.users || {};
            }, (response) => {
                console.log(['failed', response]);
            });
        },
    }
};
</script>

<style scoped>
    .registered-users {
        background-color: rgba(0,0,0, 0.4);
    }
    .users-list {
        font-size: 1vw;
        padding: 0 20px;
    }
    .users-list > li a, .users-list > li a:hover, .users-list > li a:active {
        color: white;
    }
    .user-list > li a img {
        height: 90px;
        width: 90px;
        display: inline;

    }
    .user-list > li:hover {
        background-color: rgba(0,0,0, 0.3);
        border-radius: 4px;
    }
</style>
