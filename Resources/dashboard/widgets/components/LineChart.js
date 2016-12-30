import { Line } from 'vue-chartjs';

export default {
    extends: Line,
    name: 'line-chart',
    props: ['data', 'options'],

    mounted () {
        console.log('created the line-chart component');
        Event.listen('graph-data:update', function(data, options) {
            console.log('got an update to the graph-data');
            this.renderChart(data, options);
        });
    }
};
