export function initProduct() {
    // Keep empty for now if no dom-ready init needed, but good to have
}

export function changeQty(delta) {
    const input = document.getElementById('qtyInput');
    if (!input) return;
    let val = parseInt(input.value) + delta;
    const min = parseInt(input.min) || 1;
    const max = parseInt(input.max) || 999;
    if (val < min) val = min;
    if (val > max) val = max;
    input.value = val;
}

// Expose changeQty globally for onclick handlers
window.changeQty = changeQty;
