import './bootstrap';

window.Echo.channel('menuOrder')
    .listen('CartUpdated', (e) => {
        if (tableId == e.tableId) {
            const cartTotalElement = document.querySelector("#cart-total");
            const cartQuantityElement = document.querySelector("#cart-quantity");

            if (cartTotalElement) cartTotalElement.textContent = `Gọi món (${e.item})đ`;
            if (cartQuantityElement) {
                e.amount = new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                }).format(e.amount)
                cartQuantityElement.textContent = e.amount;
            }
        }
    });