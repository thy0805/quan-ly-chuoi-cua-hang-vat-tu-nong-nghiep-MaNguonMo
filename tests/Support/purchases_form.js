import fs from 'node:fs';
import vm from 'node:vm';
import assert from 'node:assert/strict';
const events = {};
let printed = 0;
const element = attributes => ({attributes: {...attributes}, hasAttribute(key) { return key in this.attributes; }, getAttribute(key) { return this.attributes[key]; }, setAttribute(key, value) { this.attributes[key] = value; }});
function row(index) {
    const fields = ['product_id', 'quantity', 'unit_price'].map(field => element({id: `${field}-${index}`, name: `lines[${index}][${field}]`}));
    const labels = fields.map(field => element({for: field.attributes.id}));
    const number = {textContent: ''};
    const remove = {disabled: false};
    const item = {fields, labels, querySelector(selector) { return selector === '.line-number' ? number : selector === '.remove-purchase-line' ? remove : fields[2]; }, remove() { rows.splice(rows.indexOf(item), 1); }};
    fields[0].name = fields[0].attributes.name;
    fields[0].closest = () => item;
    fields[0].matches = selector => selector === '.purchase-product';
    return item;
}
let rows = [row(0), row(2)];
const add = {disabled: false, addEventListener(event, callback) { events.add = callback; }};
const lines = {querySelectorAll(selector) { return selector === '.purchase-product' ? rows.map(item => item.fields[0]) : rows; }, addEventListener(event, callback) { events[event] = callback; }, appendChild(fragment) { rows.push(fragment.item); }};
const template = {content: {cloneNode() { const item = row('__INDEX__'); return {item, querySelectorAll() { return [...item.fields, ...item.labels]; }}; }}};
const document = {querySelector(selector) { return selector === '#purchase-lines' ? lines : selector === '#purchase-line-template' ? template : selector === '#add-purchase-line' ? add : {addEventListener(event, callback) { events.print = callback; }}; }};
vm.runInNewContext(fs.readFileSync('public/assets/purchases.js', 'utf8'), {document, window: {print() { printed += 1; }}});
events.add();
assert.equal(rows[2].fields[0].getAttribute('name'), 'lines[3][product_id]');
assert.equal(rows[2].labels[0].getAttribute('for'), rows[2].fields[0].getAttribute('id'));
const target = rows[2];
events.click({target: {closest: () => ({closest: () => target})}});
events.add();
assert.equal(rows[2].fields[0].getAttribute('name'), 'lines[4][product_id]');
const product = rows[0].fields[0];product.selectedOptions = [{dataset: {price: '12345.67'}}];
events.change({target: product});assert.equal(rows[0].fields[2].value, '12345.67');
while (rows.length < 50) events.add();events.add();assert.equal(rows.length, 50);assert.equal(add.disabled, true);
while (rows.length > 1) { const last = rows[rows.length - 1];events.click({target: {closest: () => ({closest: () => last})}}); }
assert.equal(rows[0].querySelector('.remove-purchase-line').disabled, true);
events.print();assert.equal(printed, 1);
process.stdout.write(JSON.stringify({status: 'PASS', scope: 'Node VM with small DOM test double; not a real browser', assertions: 8, checks: ['sparse old-input indexes do not collide', 'labels match new field ids', 'removed indexes are not reused', 'decimal price stays a string', '50 line cap', 'add control disabled at cap', 'one line minimum', 'print handler delegates']}));
