<template>
    <grid :position="grid">
        <section class="daily-register-count" v-if="hasData">
            <line-chart :data="data" :options="options" :height="height"></line-chart>
        </section>
        <section class="daily-register-count" v-else>
            <div class="loading">Loading <i class="fa fa-fw fa-spinner fa-spin"></i></div>
        </section>
    </grid>
</template>

<script>
import Grid from '../../../../Admin/Resources/dashboard/js/mixins/grid.js';
import API from '../../../../Admin/Resources/dashboard/js/mixins/api.js';
import LineChart from './components/LineChart.js';

export default {
    name: 'auth-daily-register-count',
    components: {
        Grid, LineChart
    },
    mixins: [API],
    props: ['grid'],

    data() {
        return {
            height: 200,
            data: null,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales:{
                    xAxes: [{
                        display: false
                    }],
                    yAxes: [{
                        display: false
                    }]
                }
            }
        }
    },

    created () {
        console.log('daily-register-count created');
        // this.getData();
    },

    computed: {
        hasData() {
            return this.data !== null;
        }
    },

    methods: {
        getData() {
            console.log('daily-register-count getData()');

            this.api('get', '/api/widget/auth/daily-register-count', {}).then((response) => {
                this.data = response.body || {};

                console.log('emitted data');
                Event.fire('graph-data:update', {
                    data: this.data,
                    options: this.options
                });
            }, (response) => {
                console.log(['failed', response]);
            });
        }
    }
};
</script>

<style scoped>
    .daily-register-count {
        background-color: #000;
    }
    .daily-register-count > div {
        display: list-item;
    }
</style>
