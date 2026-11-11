const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const path = require('node:path');
(async () => {
    const alert = { innerHTML: '' };
    const start = { value: '2026-11-10', reportValidity: () => true };
    const end = { value: '2026-11-09', reportValidity: () => true };
    let requests = 0;
    const ctx = {
        document: {
            getElementById: id => ({ liveAlertPlaceholder: alert, startDate: start, endDate: end })[id],
            addEventListener: () => {}
        },
        fetch: () => { ++requests; throw Error('Invalid range must not request a download'); }
    };
    vm.createContext(ctx);
    vm.runInContext(fs.readFileSync(path.resolve(__dirname, '../../../public/download/index.js'), 'utf8'), ctx);
    await vm.runInContext('downloadHistory()', ctx);
    assert.match(alert.innerHTML, /Start date must be on or before end date/);
    assert.equal(requests, 0);
    start.value = '';
    start.reportValidity = () => false;
    await vm.runInContext('downloadHistory()', ctx);
    assert.equal(requests, 0);
    console.log('Reversed and missing dates are blocked before a request or spinner starts.');
})().catch(error => { console.error(error); process.exitCode = 1; });
