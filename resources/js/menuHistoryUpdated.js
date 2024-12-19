import './bootstrap';

Echo.channel('items')
    .listen('ItemUpdated', (data) => {
        if (data.tableId == tableId) {
            updateItemList(data.orderItems);
            noti(data.noti);
        }
    });
function noti(items) {
    Swal.fire({
        icon: 'success',
        title: 'Thông báo',
        text: items,
        timer: 3000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
}
function updateItemList(items) {
    const orderContainer = document.getElementById('order-container');
    const btnSubb = document.getElementById('btn-subb');
    let totalAmount = 0;
    orderContainer.innerHTML = '';

    items.forEach(item => {
        item.price = new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(item.price);

        let statusText = "";
        let statusColor = "";

        switch (item.status) {
            case "chờ xử lý":
                statusText = "Chờ xác nhận";
                statusColor = "#FFA500";
                break;
            case "đang xử lý":
                statusText = "Đã duyệt";
                statusColor = "#007BFF";
                break;
            case "hoàn thành":
                statusText = "Đã hoàn thành";
                statusColor = "#28A745";
                break;
            case "hủy":
                statusText = "Đã hủy";
                statusColor = "#DC3545";
                break;
            default:
                statusText = "Không xác định";
                statusColor = "#6C757D";
        }

        const itemHtml = `
            <div class="order-item" data-id="${item.id}" data-type="${item.item_type}" data-status="${item.status}">
                <img src="/storage/${item.item_type == '1' ? item.dish.image : item.combo.image}">
                <div class="item-details">
                    <p class="mb-0">${item.item_type == '1' ? item.dish.name : item.combo.name}</p>
                    <small>${item.price}</small>
                </div>
                <div class="item-controls">
                    <span class="mx-2 quantity" style="color: ${statusColor}">${statusText}</span>
                    <br>
                    <span class="mx-2 quantity">X ${item.quantity}</span>
                </div>
            </div>
        `;
        orderContainer.insertAdjacentHTML('beforeend', itemHtml);

        if (item.status !== "hủy") {
            totalAmount += parseFloat(item.price.replace(/[^\d.-]/g, '')) * 1000 * item.quantity;
        }
    });

    btnSubb.innerHTML = new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(totalAmount);
}

