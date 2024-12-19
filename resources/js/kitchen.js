import './bootstrap';

// Function to generate card templates for a dish
function generateCardTemplate(item, updatedAtKey, buttonClass, buttonTitle, buttonIcon, buttonColor) {
    const { id, combo, dish, formatted_created_at, formatted_updated_at, quantity, count_cancel, order } = item;
    const table = order.tables[0];
    const updatedAt = item[updatedAtKey];
    let cancelInfo = count_cancel > 0
        ? `<div class="row">
                <p>Hủy <span class="text-danger">${count_cancel}</span> vào lúc ${formatted_updated_at}</p>
           </div>`
        : '';

    let actionButton = quantity > 0
        ? `<button class="btn ${buttonClass}" title="${buttonTitle}">
                <i class="${buttonIcon}"></i>
           </button>`
        : `<button class="btn btn-secondary delete" title="Xóa">
                <i class="fa-solid fa-trash"></i>
           </button>`;

    return `
        <div class="order-card row" data-item-id="${id}" data-item-type="${item.item_type}" data-table-id="${table.id}">
            <div class="col-md-6">
                <strong>${item.item_type == 2 ? combo?.name : dish?.name}</strong>
                <p>${updatedAt}</p>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4">
                        <strong>Số lượng</strong>
                        <p>${quantity}</p>
                    </div>
                    <div class="col-md-4">
                        <strong>Bàn</strong>
                        <p>${table.table_number}</p>
                    </div>
                    <div class="col-md-4 btn-group-custom">
                        ${actionButton}
                    </div>
                </div>
                ${cancelInfo}
            </div>
        </div>
    `;
}

// Common function to update the innerHTML of an element
function updateUI(containerId, items, updatedAtKey, buttonClass, buttonTitle, buttonIcon) {
    const container = document.getElementById(containerId);
    if (container) {
        container.innerHTML = items.map(item =>
            generateCardTemplate(
                item,
                updatedAtKey,
                buttonClass,
                buttonTitle,
                buttonIcon
            )
        ).join('');
    }
}

// Kitchen Processing Listener
window.Echo.channel('kitchen')
    .listen('ProcessingDishes', (e) => {
        if (e.noti) {
            let audio = new Audio('sounds/mixkit-doorbell-tone-2864.wav');
            audio.play();
            Swal.fire({
                icon: 'info',
                title: 'Thông báo',
                text: e.noti,
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }

        updateUI('DangCheBien', e.items, 'formatted_created_at', 'btn-danger cook-all', 'Chế biến toàn bộ', 'fa-solid fa-forward');
    });

// Kitchen Provide Listener
window.Echo.channel('kitchenn')
    .listen('ProvideDishes', (e) => {
        updateUI('ChoCungUng', e.items, 'formatted_updated_at', 'btn-success done-all', 'Cung ứng toàn bộ', 'fa-solid fa-forward');
    });
