const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const path = require('node:path');
const root = path.resolve(__dirname, '../../..');
const moment = require(path.join(root, 'public/libraries/moment.2.30.1.min.js'));
(async () => {
    for (const timezone of ['Africa/Johannesburg', 'America/New_York', 'Pacific/Auckland']) {
        process.env.TZ = timezone;
        let request;
        const dateInput = { value: '2026-11-10' };
        const ctx = {
            moment, console, window: {},
            document: {
                getElementById: id => id === 'historyDate' ? dateInput : { getContext: () => ({}) },
                querySelectorAll: () => []
            },
            fetch: async url => { request = url; return { ok: true, json: async () => [] }; },
            Chart: function (_, config) { Object.assign(this, config); this.update = () => {}; }
        };
        vm.createContext(ctx);
        vm.runInContext(fs.readFileSync(path.join(root, 'public/graphs/index.js'), 'utf8'), ctx);
        await vm.runInContext('fetchHistory()', ctx);
        const url = new URL(request, 'https://weather.sun.ac.za');
        assert.equal(url.searchParams.get('start'), '1794261600', timezone);
        assert.equal(url.searchParams.get('end'), '1794347999', timezone);
        ctx.document.querySelectorAll = () => [{ checked: true, id: 'check_AirTC_Avg' }];
        vm.runInContext("originalData = [{TimeStamp:'2026-11-10 00:00:00', AirTC_Avg:20}]; updateChartDatasets()", ctx);
        assert.equal(vm.runInContext('historyChart.data.datasets[0].data[0].x.getTime()', ctx), 1794261600000);
        assert.equal(vm.runInContext('historyChart.options.scales.x.ticks.callback(1794261600000)', ctx), '00:00');
        assert.equal(vm.runInContext("historyChart.options.plugins.tooltip.callbacks.title([{parsed:{x:1794261600000}}])", ctx), '2026-11-10 00:00 SAST');
    }
    console.log('Graph date boundaries and labels pass in three timezones.');
})().catch(error => { console.error(error); process.exitCode = 1; });
