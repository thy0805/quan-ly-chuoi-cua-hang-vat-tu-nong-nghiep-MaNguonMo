document.querySelector('[data-print-invoice]')?.addEventListener('click', () => window.print());
const saleLines = document.querySelector('#sale-lines');
const saleTemplate = document.querySelector('#sale-line-template');
if (saleLines && saleTemplate) {
    let nextLine = Array.from(saleLines.querySelectorAll('.sale-product'), element => Number(element.name.match(/^lines\[(\d+)\]/)?.[1] ?? -1)).reduce((highest, index) => Math.max(highest, index), -1) + 1;
    const updateLines = () => {
        const rows = saleLines.querySelectorAll('.sale-line');
        rows.forEach((row, index) => {
            row.querySelector('.line-number').textContent = String(index + 1);
            row.querySelector('.remove-sale-line').disabled = rows.length === 1;
        });
        document.querySelector('#add-sale-line').disabled = rows.length >= 50;
    };
    document.querySelector('#add-sale-line').addEventListener('click', () => {
        if (saleLines.querySelectorAll('.sale-line').length >= 50) return;
        const fragment = saleTemplate.content.cloneNode(true);
        fragment.querySelectorAll('[id], [name], label[for]').forEach(element => {
            for (const attribute of ['id', 'name', 'for']) {
                if (element.hasAttribute(attribute)) element.setAttribute(attribute, element.getAttribute(attribute).replaceAll('__INDEX__', String(nextLine)));
            }
        });
        nextLine += 1;
        saleLines.appendChild(fragment);
        updateLines();
    });
    saleLines.addEventListener('click', event => {
        const remove = event.target.closest('.remove-sale-line');
        if (remove && saleLines.querySelectorAll('.sale-line').length > 1) { remove.closest('.sale-line').remove(); updateLines(); }
    });
    saleLines.addEventListener('change', event => {
        if (event.target.matches('.sale-product')) event.target.closest('.sale-line').querySelector('.sale-price').value = event.target.selectedOptions[0]?.dataset.price ?? '';
    });
    updateLines();
}
